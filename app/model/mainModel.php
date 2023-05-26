<?php
class MainModel
{
    private $db_server;
    private $db_database;

    public function __construct()
    {
        require_once('app/module/login.php');

        $this->db_server = $db_server;
        $this->db_database = $db_database;
    }

    public function postSignup($email, $username, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $query = "INSERT INTO user (email, username, password, auth) VALUES ('$email', '$username', '$password', 'NULL')";
        $result = mysqli_query($this->db_server, $query);
        return '200 OK';
    }

    public function postSignin($email, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $query = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($this->db_server, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row['password'] == $password)
            return $row['username'];
        else
            return null;
    }

    public function postGallary($currentPage, $size)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $currentRow = ($currentPage - 1) * $size;
        //post와 image를 imageId 기준으로 inner join하고, 생성순으로 내림차순 정렬해서
        //페이지와 사이즈에 해당하는 개수의 데이터를 불러오는 쿼리문
        $query = "SELECT * FROM post JOIN image ON post.imageId = image.imageId
        ORDER BY postId DESC LIMIT $currentRow, $size";
        $result = mysqli_query($this->db_server, $query);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $rownum_query = "SELECT * FROM post";
        $rownum_result = mysqli_query($this->db_server, $rownum_query);
        $rownum = mysqli_num_rows($rownum_result);

        $response = array();
        $response['rownum'] = $rownum;
        $response['data'] = $row;
        return $response;
    }
    public function postCapture($image, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userIdQuery = "SELECT * FROM user WHERE username = '$username'";
        $userIdResult = mysqli_query($this->db_server, $userIdQuery);
        $userId = mysqli_fetch_array($userIdResult, MYSQLI_NUM)[0];

        $insertQuery = "INSERT INTO image (date, image, userId) VALUES (NOW(), '$image', '$userId[0]')";
        mysqli_query($this->db_server, $insertQuery);

        $resultQuery = "SELECT * FROM image WHERE userId = '$userId' ORDER BY imageId DESC";
        $result = mysqli_query($this->db_server, $resultQuery);
        $images = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $images;
    }
    public function getImagesByUsername($username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userIdQuery = "SELECT * FROM user WHERE username = '$username'";
        $userIdResult = mysqli_query($this->db_server, $userIdQuery);
        $userId = mysqli_fetch_array($userIdResult, MYSQLI_NUM)[0];

        $resultQuery = "SELECT * FROM image WHERE userId = '$userId' ORDER BY imageId DESC";
        $result = mysqli_query($this->db_server, $resultQuery);
        $images = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $images;
    }

    public function postImage($imageId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        //유저 아이디 기반으로 이미지 객체를 가져오고,
        $imagesQuery = "SELECT * FROM image WHERE imageId = '$imageId' ORDER BY imageId DESC";
        $imagesResult = mysqli_query($this->db_server, $imagesQuery);
        $images = mysqli_fetch_array($imagesResult, MYSQLI_ASSOC);

        //그 이미지 객체를 바탕으로 post에 insert 쿼리 날리기
        $userId = $images['userId'];
        $postQuery = "INSERT INTO post (date, likes, userId, imageId) VALUES (NOW(), null, '$userId', '$imageId')";
        $postResult = mysqli_query($this->db_server, $postQuery);
    }

    public function getPostByPostId($postId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $postQuery = "SELECT * FROM post WHERE postId = '$postId'";
        $postResult = mysqli_query($this->db_server, $postQuery);
        $post = mysqli_fetch_array($postResult, MYSQLI_ASSOC);

        $imageId = $post['imageId'];
        $imageQuery = "SELECT * FROM image WHERE imageId = '$imageId' ORDER BY imageId DESC";
        $imageResult = mysqli_query($this->db_server, $imageQuery);
        $image = mysqli_fetch_array($imageResult, MYSQLI_ASSOC);

        $result = array();
        $result['likes'] = $post['likes'];
        $result['image'] = $image['image'];
        return $result;
    }

    public function postLikes($postId, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $likesQuery = "UPDATE post SET likes = 
        CASE 
            WHEN likes IS NULL THEN 1
            ELSE likes + 1
        END
        WHERE postId = '$postId'";
        $likesResult = mysqli_query($this->db_server, $likesQuery);
    }

    public function postComment($comment, $postId, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userIdQuery = "SELECT * FROM user WHERE username = '$username'";
        $userIdResult = mysqli_query($this->db_server, $userIdQuery);
        $userId = mysqli_fetch_array($userIdResult, MYSQLI_NUM)[0];

        $commentQuery = "INSERT INTO comment (comment, date, userId, postId) VALUES ('$comment', NOW(), '$userId', '$postId')";
        $commentResult = mysqli_query($this->db_server, $commentQuery);
    }
    public function getCommentByPostId($postId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $query = "SELECT * FROM comment JOIN user ON comment.userId = user.userId
        WHERE comment.postId = '$postId'
        ORDER BY commentId ASC";
        $result = mysqli_query($this->db_server, $query);
        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $fetch;
    }

    public function getPostsByUsername($username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userIdQuery = "SELECT * FROM user WHERE username = '$username'";
        $userIdResult = mysqli_query($this->db_server, $userIdQuery);
        $userId = mysqli_fetch_array($userIdResult, MYSQLI_ASSOC)['userId'];

        $postsQuery = "SELECT * FROM post JOIN image ON post.imageId = image.imageId
        WHERE post.userId = '$userId' ORDER BY postId DESC";
        $postsResult = mysqli_query($this->db_server, $postsQuery);
        $posts = mysqli_fetch_all($postsResult, MYSQLI_ASSOC);

        return $posts;
    }

    public function patchUsername($username, $change)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $dupQuery = "SELECT * FROM user WHERE username='$change'";
        $dupResult = mysqli_query($this->db_server, $dupQuery);
        $dup = mysqli_fetch_array($dupResult, MYSQLI_ASSOC)['username'];

        if ($dup === $change)
            return false;
        else
        {
            $updateQuery = "UPDATE user SET username='$change' WHERE username='$username'";
            $updateResult = mysqli_query($this->db_server, $updateQuery);
            return true;
        }
    }
    public function patchEmail($email, $change)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $dupQuery = "SELECT * FROM user WHERE email='$change'";
        $dupResult = mysqli_query($this->db_server, $dupQuery);
        $dup = mysqli_fetch_array($dupResult, MYSQLI_ASSOC)['email'];

        if ($dup === $change)
            return false;
        else
        {
            $updateQuery = "UPDATE user SET email='$change' WHERE email='$email'";
            $updateResult = mysqli_query($this->db_server, $updateQuery);
            return true;
        }
    }
}

?>