<?php

class MainModel
{
    private $mysqli;

    public function __construct($db_name)
    {
        require_once('app/module/login.php');

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli($db_hostname, $db_user, $db_password, $db_name);
        $this->mysqli->set_charset('utf8mb4');
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
        $query = "INSERT INTO user (email, username, password, auth, notice) VALUES (?, ?, ?, 'always', 'always')";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sss", $email, $username, $hashed_password);
        $stmt->execute();

        return $this->createResult(true, '성공', NULL);
    }
    
    public function checkDupSignup ($email, $username) {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $usernameDup = $result->fetch_array(MYSQLI_ASSOC);
        
        if (isset($usernameDup))
            return $this->createResult(false, '중복된 닉네임입니다.', NULL);;
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $emailDup = $result->fetch_array(MYSQLI_ASSOC);
        
        if (isset($emailDup))
            return $this->createResult(false, '중복된 이메일입니다.', NULL);


        return $this->createResult(true, '성공', NULL);
    }

    public function postSignin($email, $password)
    {
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        
        if (isset($row['password']) && password_verify($password ,$row['password']))
        {
            if (isset($row['auth']) && $row['auth'] === 'always')
                return $this->createResult(true, '인증 필요', $row['username']);
            else
                return $this->createResult(true, '로그인 성공', $row['username']);
        }
        else
            return $this->createResult(false, '로그인 실패', null);
    }

    public function postGallary($currentPage, $size)
    {
        $currentRow = ($currentPage - 1) * $size;
        //post와 image를 imageId 기준으로 inner join하고, 생성순으로 내림차순 정렬해서
        //페이지와 사이즈에 해당하는 개수의 데이터를 불러오는 쿼리문
        $query = "SELECT * FROM post JOIN image ON post.imageId = image.imageId
        ORDER BY postId DESC LIMIT ?, ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ii", $currentRow, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_all(MYSQLI_ASSOC);

        $query = "SELECT * FROM post";
        $result = $this->mysqli->query($query);
        $rownum = $result->num_rows;

        $response = array();
        $response['rownum'] = $rownum;
        $response['data'] = $row;
        return $this->createResult(true, '갤러리 데이터 전송', $response);
    }

    public function postCapture($image, $username)
    {
        $userId = $this->getUserIdbyUsername($username)['data'];
        $query = "INSERT INTO image (date, image, userId) VALUES (NOW(), ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ss", $image, $userId);
        $stmt->execute();

        $query = "SELECT * FROM image WHERE userId = ? ORDER BY imageId DESC";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = $result->fetch_all(MYSQLI_ASSOC);

        return $this->createResult(true, '이미지 저장 성공', $images);
    }

    public function getImagesByUsername($username)
    {
        $userId = $this->getUserIdbyUsername($username)['data'];
        $query = "SELECT * FROM image WHERE userId = ? ORDER BY imageId DESC";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = $result->fetch_all(MYSQLI_ASSOC);

        return $this->createResult(true, '이미지 조회', $images);
    }

    public function postImage($imageId)
    {
        //게시물 중복 체크
        $query = "SELECT * FROM post WHERE imageId= ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $dup = $result->fetch_array(MYSQLI_ASSOC);
        
        if (isset($dup))
            return $this->createResult(false, '중복', NULL);
        
        //유저 아이디 기반으로 이미지 객체를 가져오고,
        $query = "SELECT * FROM image WHERE imageId = ? ORDER BY imageId DESC";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = $result->fetch_array(MYSQLI_ASSOC);

        //그 이미지 객체를 바탕으로 post에 insert 쿼리 날리기
        $userId = $images['userId'];
        $query = "INSERT INTO post (date, likes_count, userId, imageId) VALUES (NOW(), null, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ii", $userId, $imageId);
        $stmt->execute();
        $postId = $stmt->insert_id;

        return $this->createResult(true, '업로드 성공', $postId);
    }

    public function getPostByPostId($postId)
    {
        $query = "SELECT * FROM post WHERE postId = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_array(MYSQLI_ASSOC);

        if (!$post)
            return $this->createResult(false, '게시물 조회 실패', null);

        $imageId = $post['imageId'];
        $query = "SELECT * FROM image WHERE imageId = ? ORDER BY imageId DESC";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_array(MYSQLI_ASSOC);

        $rst = array();
        $rst['likes_count'] = $post['likes_count'];
        $rst['image'] = $image['image'];
        $rst['userId'] = $post['userId'];

        return $this->createResult(true, '게시물 조회 성공', $rst);
    }

    public function postLikes($postId, $username)
    {
        $userId = $this->getUserIdbyUsername($username)['data'];
        $query = "INSERT IGNORE INTO likes(userId, postId) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
        $affectedRow = $stmt->affected_rows;

        $query = "UPDATE post SET likes_count = 
        ( SELECT COUNT(*) FROM likes WHERE postId = ? )
        WHERE postId = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ii", $postId, $postId);
        $stmt->execute();
        
        if ($affectedRow === 0)
            return $this->createResult(false, '중복', NULL);
        else
            return $this->createResult(true, '추천 성공', NULL);
    }

    public function postComment($comment, $postId, $username)
    {
        $userId = $this->getUserIdbyUsername($username)['data'];
        $query = "INSERT INTO comment (comment, date, userId, postId) VALUES (?, NOW(), ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sii", $comment, $userId, $postId);
        $stmt->execute();

        return $this->createResult(true, '댓글 추가 성공', NULL);
    }

    public function getCommentByPostId($postId)
    {
        $query = "SELECT * FROM comment JOIN user ON comment.userId = user.userId
        WHERE comment.postId = ?
        ORDER BY commentId ASC";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $fetch = $result->fetch_all(MYSQLI_ASSOC);

        return $this->createResult(true, '댓글 조회 성공', $fetch);
    }

    public function getPostsByUsername($username)
    {
        $userId = $this->getUserIdbyUsername($username)['data'];

        $query = "SELECT * FROM post JOIN image ON post.imageId = image.imageId
        WHERE post.userId = ? ORDER BY postId DESC";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);

        return $this->createResult(true, '게시물 조회 성공', $posts);
    }

    public function patchUsername($username, $change)
    {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $change);
        $stmt->execute();
        $result = $stmt->get_result();
        $dup = $result->fetch_array(MYSQLI_ASSOC)['username'];

        if ($dup === $change)
            return $this->createResult(false, '중복된 닉네임입니다', NULL);
        else
        {
            $query = "UPDATE user SET username= ? WHERE username = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("ss", $change, $username);
            $stmt->execute();
            
            return $this->createResult(true, '닉네임 변경 성공', NULL);
        }
    }

    public function patchEmail($email, $change)
    {
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $change);
        $stmt->execute();
        $result = $stmt->get_result();
        $dup = $result->fetch_array(MYSQLI_ASSOC)['email'];

        if ($dup === $change)
            return $this->createResult(false, '중복된 이메일입니다.', NULL);
        else
        {
            $query = "UPDATE user SET email = ? WHERE email = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("ss", $change, $email);
            $stmt->execute();

            return $this->createResult(true, '이메일 변경 성공', NULL);
        }
    }
    
    public function patchPassword($origin, $new, $username)
    {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $originCheck = $result->fetch_array(MYSQLI_ASSOC);

        if (empty($originCheck) || !password_verify($origin, $originCheck['password']))
            return $this->createResult(false, '기존 비밀번호가 틀렸습니다', NULL);
        $query = "UPDATE user SET password = ? WHERE username = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ss", password_hash($new, PASSWORD_DEFAULT), $username);
        $stmt->execute();

        return $this->createResult(true, '비밀번호 변경 성공', NULL);
    }

    public function deleteComment($commentId)
    {
        $query = "DELETE FROM comment WHERE commentId=?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();

        return $this->createResult(true, '댓글 삭제 성공', NULL);
    }

    public function patchComment($commentId, $newComment)
    {
        $query = "UPDATE comment SET comment = ? WHERE commentId = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("si", $newComment, $commentId);
        $stmt->execute();

        return $this->createResult(true, '댓글 수정 성공', NULL);
    }

    public function getUserIdbyUsername($username)
    {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $userId = $result->fetch_array(MYSQLI_ASSOC)['userId'];

        return $this->createResult(true, '닉네임 조회 성공', $userId);
    }

    public function deletePost($postId)
    {
        $query = "DELETE FROM post WHERE postId=?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $postId);
        $stmt->execute();

        return $this->createResult(true, '게시물 삭제 성공', NULL);
    }

    public function deleteImage($imageId)
    {
        $query = "DELETE FROM image WHERE imageId=?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $imageId);
        $stmt->execute();

        return $this->createResult(true, '이미지 삭제 성공', NULL);
    }

    public function getImageByImageId($imageId)
    {
        $query = "SELECT * FROM image WHERE imageId = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_array(MYSQLI_ASSOC)['image'];

        return $this->createResult(true, '이미지 조회 성공', $image);
    }
    
    public function getLikesPostsByUsername($username)
    {
        $userId = $this->getUserIdbyUsername($username)['data'];

        $query = "SELECT * FROM likes WHERE userId = ? ORDER BY postId DESC";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $likes = $result->fetch_all(MYSQLI_ASSOC);

        $rst = array();
        foreach($likes as $ele)
        {
            $post = $this->getPostByPostId($ele['postId'])['data'];
            $post['postId'] = $ele['postId'];
            array_push($rst, $post);
        }
        return $this->createResult(true, '좋아요 조회 성공', $rst);
    }
}

?>