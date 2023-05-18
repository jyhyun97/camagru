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

    public function post_signup($email, $username, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $query = "INSERT INTO user (email, username, password, auth) VALUES ('$email', '$username', '$password', 'NULL')";
        $result = mysqli_query($this->db_server, $query);
        return '200 OK';
    }

    public function post_signin($email, $password)
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

    public function post_gallary($currentPage, $size)
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
    public function post_capture($image, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userIdQuery = "SELECT * FROM user WHERE username = '$username'";
        $userIdResult = mysqli_query($this->db_server, $userIdQuery);
        $userId = mysqli_fetch_array($userIdResult, MYSQLI_NUM)[0];

        $insertQuery = "INSERT INTO image (date, image, userId) VALUES (NOW(), '$image', '$userId[0]')";
        mysqli_query($this->db_server, $insertQuery);

        $resultQuery = "SELECT * FROM image WHERE userId = '$userId'";
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

        $resultQuery = "SELECT * FROM image WHERE userId = '$userId'";
        $result = mysqli_query($this->db_server, $resultQuery);
        $images = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $images;
    }

    public function post_image($imageId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        //유저 아이디 기반으로 이미지 객체를 가져오고,
        $imagesQuery = "SELECT * FROM image WHERE imageId = '$imageId'";
        $imagesResult = mysqli_query($this->db_server, $imagesQuery);
        $images = mysqli_fetch_array($imagesResult, MYSQLI_ASSOC);

        print_r($images);
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
        $imageQuery = "SELECT * FROM image WHERE imageId = '$imageId'";
        $imageResult = mysqli_query($this->db_server, $imageQuery);
        $image = mysqli_fetch_array($imageResult, MYSQLI_ASSOC);

        $result = array();
        $result['likes'] = $post['likes'];
        $result['image'] = $image['image'];
        return $result;
    }
}

?>