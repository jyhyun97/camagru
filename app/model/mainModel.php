<?php
class MainModel
{
    private $db_server;
    private $db_database;

    public function __construct($db_name)
    {
        require_once('app/module/login.php');

        $this->db_server = $db_server;
        $this->db_database = $db_name;
    }

    private function createResult($success, $message, $data)
    {
        $result = array();
        $result['success'] = $success;
        $result['message'] = $message;
        $result['data'] = $data;
        return $result;
    }

    public function postSignup($email, $username, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        
        $usernameDupQuery = "SELECT * FROM user WHERE username='$username'";
        $usernameDupResult = mysqli_query($this->db_server, $usernameDupQuery);
        $usernameDup = mysqli_fetch_array($usernameDupResult, MYSQLI_ASSOC);

        if (isset($usernameDup))
            return $this->createResult(false, '중복된 닉네임입니다.', NULL);;
        $emailDupQuery = "SELECT * FROM user WHERE email='$email'";
        $emailDupResult = mysqli_query($this->db_server, $emailDupQuery);
        $emailDup = mysqli_fetch_array($emailDupResult, MYSQLI_ASSOC);
        
        if (isset($emailDup))
            return $this->createResult(false, '중복된 이메일입니다.', NULL);

        $insertQuery = "INSERT INTO user (email, username, password, auth) VALUES ('$email', '$username', '$password', 'NULL')";
        $insert = mysqli_query($this->db_server, $insertQuery);

        return $this->createResult(true, '성공', NULL);
    }

    public function postSignin($email, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $query = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($this->db_server, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row['password'] == $password)
            return $this->createResult(true, '로그인 성공', $row['username']);
        else
            return $this->createResult(false, '로그인 실패', null);
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
        return $this->createResult(true, '갤러리 데이터 전송', $response);
    }

    public function postCapture($image, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userId = $this->getUserIdbyUsername($username)['data'];

        $insertQuery = "INSERT INTO image (date, image, userId) VALUES (NOW(), '$image', '$userId')";
        mysqli_query($this->db_server, $insertQuery);

        $imagesQuery = "SELECT * FROM image WHERE userId = '$userId' ORDER BY imageId DESC";
        $imagesResult = mysqli_query($this->db_server, $imagesQuery);
        $images = mysqli_fetch_all($imagesResult, MYSQLI_ASSOC);

        return $this->createResult(true, '이미지 저장 성공', $images);
    }

    public function getImagesByUsername($username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userId = $this->getUserIdbyUsername($username)['data'];

        $imagesQuery = "SELECT * FROM image WHERE userId = '$userId' ORDER BY imageId DESC";
        $imagesResult = mysqli_query($this->db_server, $imagesQuery);
        $images = mysqli_fetch_all($imagesResult, MYSQLI_ASSOC);

        return $this->createResult(true, '이미지 조회', $images);
    }

    public function postImage($imageId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        //게시물 중복 체크
        $dupQuery = "SELECT * FROM post WHERE imageId='$imageId'";
        $dupResult = mysqli_query($this->db_server, $dupQuery);
        $dup = mysqli_fetch_array($dupResult, MYSQLI_ASSOC);

        if (isset($dup))
            return $this->createResult(false, '중복', NULL);
        //유저 아이디 기반으로 이미지 객체를 가져오고,
        $imagesQuery = "SELECT * FROM image WHERE imageId = '$imageId' ORDER BY imageId DESC";
        $imagesResult = mysqli_query($this->db_server, $imagesQuery);
        $images = mysqli_fetch_array($imagesResult, MYSQLI_ASSOC);

        //그 이미지 객체를 바탕으로 post에 insert 쿼리 날리기
        $userId = $images['userId'];
        $postQuery = "INSERT INTO post (date, likes_count, userId, imageId) VALUES (NOW(), null, '$userId', '$imageId')";
        $postResult = mysqli_query($this->db_server, $postQuery);
        $postId = mysqli_insert_id($this->db_server);
        return $this->createResult(true, '업로드 성공', $postId);
    }

    public function getPostByPostId($postId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $postQuery = "SELECT * FROM post WHERE postId = '$postId'";
        $postResult = mysqli_query($this->db_server, $postQuery);
        $post = mysqli_fetch_array($postResult, MYSQLI_ASSOC);
        if (!$post)
            return $this->createResult(false, '게시물 조회 실패', null);

        $imageId = $post['imageId'];
        $imageQuery = "SELECT * FROM image WHERE imageId = '$imageId' ORDER BY imageId DESC";
        $imageResult = mysqli_query($this->db_server, $imageQuery);
        $image = mysqli_fetch_array($imageResult, MYSQLI_ASSOC);

        $result = array();
        $result['likes_count'] = $post['likes_count'];
        $result['image'] = $image['image'];
        $result['userId'] = $post['userId'];
        return $this->createResult(true, '게시물 조회 성공', $result);
    }

    public function postLikes($postId, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userId = $this->getUserIdbyUsername($username)['data'];
        $insertQuery = "INSERT IGNORE INTO likes(userId, postId)
            VALUES ('$userId', '$postId')";
        $insertResult = mysqli_query($this->db_server, $insertQuery);
        $affectedRow = mysqli_affected_rows($this->db_server);

        $likesQuery = "UPDATE post SET likes_count = 
        (
            SELECT COUNT(*) FROM likes WHERE postId = '$postId'
        )
        WHERE postId = '$postId'";
        $likesResult = mysqli_query($this->db_server, $likesQuery);
        
        if ($affectedRow === 0)
            return $this->createResult(false, '중복', NULL);
        else
            return $this->createResult(true, '추천 성공', NULL);
    }

    public function postComment($comment, $postId, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userId = $this->getUserIdbyUsername($username)['data'];

        $commentQuery = "INSERT INTO comment (comment, date, userId, postId) VALUES ('$comment', NOW(), '$userId', '$postId')";
        $commentResult = mysqli_query($this->db_server, $commentQuery);

        return $this->createResult(true, '댓글 추가 성공', NULL);
    }

    public function getCommentByPostId($postId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $query = "SELECT * FROM comment JOIN user ON comment.userId = user.userId
        WHERE comment.postId = '$postId'
        ORDER BY commentId ASC";
        $result = mysqli_query($this->db_server, $query);
        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $this->createResult(true, '댓글 조회 성공', $fetch);
    }

    public function getPostsByUsername($username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userId = $this->getUserIdbyUsername($username)['data'];

        $postsQuery = "SELECT * FROM post JOIN image ON post.imageId = image.imageId
        WHERE post.userId = '$userId' ORDER BY postId DESC";
        $postsResult = mysqli_query($this->db_server, $postsQuery);
        $posts = mysqli_fetch_all($postsResult, MYSQLI_ASSOC);

        return $this->createResult(true, '게시물 조회 성공', $posts);
    }

    public function patchUsername($username, $change)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $dupQuery = "SELECT * FROM user WHERE username='$change'";
        $dupResult = mysqli_query($this->db_server, $dupQuery);
        $dup = mysqli_fetch_array($dupResult, MYSQLI_ASSOC)['username'];

        if ($dup === $change)
            return $this->createResult(false, '중복된 닉네임입니다', NULL);
        else
        {
            $updateQuery = "UPDATE user SET username='$change' WHERE username='$username'";
            $updateResult = mysqli_query($this->db_server, $updateQuery);
            return $this->createResult(true, '닉네임 변경 성공', NULL);
        }
    }

    public function patchEmail($email, $change)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $dupQuery = "SELECT * FROM user WHERE email='$change'";
        $dupResult = mysqli_query($this->db_server, $dupQuery);
        $dup = mysqli_fetch_array($dupResult, MYSQLI_ASSOC)['email'];

        if ($dup === $change)
            return $this->createResult(false, '중복된 이메일입니다.', NULL);
        else
        {
            $updateQuery = "UPDATE user SET email='$change' WHERE email='$email'";
            $updateResult = mysqli_query($this->db_server, $updateQuery);
            return $this->createResult(true, '이메일 변경 성공', NULL);
        }
    }
    
    public function patchPassword($origin, $new, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $originCheckQuery = "SELECT * FROM user WHERE username='$username' AND password='$origin'";
        $originCheckResult = mysqli_query($this->db_server, $originCheckQuery);
        $originCheck = mysqli_fetch_array($originCheckResult, MYSQLI_ASSOC);

        if (empty($originCheck))
            return $this->createResult(false, '기존 비밀번호가 틀렸습니다', NULL);
        $updateQuery = "UPDATE user SET password='$new' WHERE username='$username'";
        $updateResult = mysqli_query($this->db_server, $updateQuery);
        return $this->createResult(true, '비밀번호 변경 성공', NULL);
    }

    public function deleteComment($commentId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $deleteQuery = "DELETE FROM comment WHERE commentId='$commentId'";
        $deleteResult = mysqli_query($this->db_server, $deleteQuery);

        return $this->createResult(true, '댓글 삭제 성공', NULL);
    }

    public function patchComment($commentId, $newComment)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $updateQuery = "UPDATE comment SET comment = '$newComment' WHERE commentId = '$commentId'";
        $updateResult = mysqli_query($this->db_server, $updateQuery);

        return $this->createResult(true, '댓글 수정 성공', NULL);
    }

    public function getUserIdbyUsername($username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userIdQuery = "SELECT * FROM user WHERE username = '$username'";
        $userIdResult = mysqli_query($this->db_server, $userIdQuery);
        $userId = mysqli_fetch_array($userIdResult, MYSQLI_ASSOC)['userId'];

        return $this->createResult(true, '닉네임 조회 성공', $userId);
    }

    public function deletePost($postId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $deleteQuery = "DELETE FROM post WHERE postId='$postId'";
        $deleteResult = mysqli_query($this->db_server, $deleteQuery);

        return $this->createResult(true, '게시물 삭제 성공', NULL);
    }

    public function deleteImage($imageId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $deleteQuery = "DELETE FROM image WHERE imageId='$imageId'";
        $deleteResult = mysqli_query($this->db_server, $deleteQuery);

        return $this->createResult(true, '이미지 삭제 성공', NULL);
    }

    public function getImageByImageId($imageId)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $imageQuery = "SELECT * FROM image WHERE imageId = '$imageId'";
        $imageResult = mysqli_query($this->db_server, $imageQuery);
        $image = mysqli_fetch_array($imageResult, MYSQLI_ASSOC)['image'];

        return $this->createResult(true, '이미지 조회 성공', $image);
    }
    
    public function getLikesPostsByUsername($username)
    {
        $userId = $this->getUserIdbyUsername($username)['data'];

        $likesQuery = "SELECT * FROM likes WHERE userId = '$userId' ORDER BY postId DESC";
        $likesResult = mysqli_query($this->db_server, $likesQuery);
        $likes = mysqli_fetch_all($likesResult, MYSQLI_ASSOC);

        $result = array();
        foreach($likes as $ele)
        {
            $post = $this->getPostByPostId($ele['postId'])['data'];
            $post['postId'] = $ele['postId'];
            array_push($result, $post);
        }
        return $this->createResult(true, '좋아요 조회 성공', $result);
    }
}

?>