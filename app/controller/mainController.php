<?php
include_once 'app/model/mainModel.php';
class MainController
{
    private static $model = null;

    public static function getModel()
    {
        if (self::$model === null) {
            self::$model = new MainModel('camagru');
        }
        return self::$model;
    }

    public static function getMain()
    {
        include_once 'app/view/main.php';
    }
    public static function getPost()
    {
        include_once 'app/view/post.php';
    }
    public static function getMypage()
    {
        include_once 'app/view/mypage.php';
    }
    public static function getUpload()
    {
        include_once 'app/view/upload.php';
    }
    public static function getSignup()
    {
        include_once 'app/view/modal/signup.php';
    }
    public static function getSignin()
    {
        include_once 'app/view/modal/signin.php';
    }

    /**
     * query 결과에 따라 중복된 이메일, 유저네임 알리기
     * [테스트]
     */
    public static function postSignup()
    {
        $data = json_decode(file_get_contents("php://input"));

        $email = $data->email;
        $username = $data->username;
        $password = $data->password;
        
        return print_r(self::postSignupProcess($email, $username, $password));
    }

    public static function postSignupProcess($email, $username, $password)
    {
        if ($email == '' || $username == '' || $password == '')
            return "빈 문자열입니다"; //추후 유효성 검사 더 넣기
        else {
            return self::getModel()->postSignup($email, $username, $password);
        }
    }

    /**
     * query 결과에 따라 로그인 실패 이유, 로그인 성공 및 세션 저장 수행
     * [테스트]
     */
    public static function postSignin()
    {
        $data = json_decode(file_get_contents("php://input"));

        $email = $data->email;
        $password = $data->password;

        return print_r(postSigninProcess($email, $password));
    }

    public static function postSigninProcess($email, $password)
    {
        $result = self::getModel()->postSignin($email, $password);
        if ($result == null)
            return "로그인 실패";
        else {
            $_SESSION['username'] = $result;
            $_SESSION['email'] = $email;
            return $result;
        }
    }
    /**
     * 메인 화면에서 갤러리 이미지 객체와 페이지 데이터를 보내주는 역할
     * 현재 페이지, 한 번에 불러오는 이미지 개수를 가지고 조회를 한다
     * [테스트]
     */
    public static function postGallary()
    {
        $data = json_decode(file_get_contents("php://input"));

        $currentPage = $data->currentPage;
        $size = $data->size;
        
        return print_r(self::postGallaryProcess($currentPage, $size));
    }

    public static function postGallaryProcess($currentPage, $size)
    {
        $result = self::getModel()->postGallary($currentPage, $size);

        // 마지막 페이지인지 판단
        if ($currentPage * $size > $result['rownum'])
            $result['lastPage'] = true;
        else
            $result['lastPage'] = false;
        return json_encode($result);
    }
    /**
     * 사용자가 캡처한 이미지를 받아서 파일을 /img 경로에 쓰고 db에 저장함
     * 이미지 합성 과정이 추가될 예정
     * 일방적으로 저장하는 내용이 대부분이라 굳이 테스트는 만들지 않아도 될 듯
     */
    public static function postCapture()
    {
        $data = json_decode(file_get_contents("php://input"));

        $username = $data->username;
        $baseImage = $data->baseImage;
        $stickyImages = $data->stickyImages;
        
        //합성();
        
        //파일 만들기
        $userId = self::getUserIdbyUsername($username);
        $newFileName = "img/" . $userId . "_" . date("Y-m-d_H:i:s") . ".png";
        $newImage = str_replace('data:image/png;base64,', '', $baseImage);
        $newImage = str_replace(' ', '+', $newImage);
        $newFile = fopen($newFileName, "w");
        fwrite($newFile, base64_decode($newImage));
        fclose($newFile);

        //db에 저장하기
        $result = self::getModel()->postCapture($newFileName, $username);
        print_r(json_encode($result));
        return;
    }

    /**
     * 마이페이지에서 내가 올린 이미지들을 가져올 때 사용
     */
    public static function getImagesByUsername($username)
    {
        return self::getModel()->getImagesByUsername($username);
    }

    /**
     * 선택한 이미지를 게시물로 업로드할 때 사용
     */
    public static function postImage()
    {
        $data = json_decode(file_get_contents("php://input"));

        $imageId = str_replace("captured-image-", "", $data->imageId);
        return self::getModel()->postImage($imageId);
    }

    /**
     * 각각의 게시물 페이지를 조회할 때 사용
     */
    public static function getPostByPostId($postId)
    {
        return self::getModel()->getPostByPostId($postId);
    }

    /**
     * 좋아요를 눌렀을 경우 사용됨
     * [테스트]
     */
    public static function postLikes()
    {
        $data = json_decode(file_get_contents("php://input"));
        
        $postId = $data->postId;
        $username = $data->username;

        $result = self::postLikesProcess($postId, $username);
        return print_r($result);
    }
    public static function postLikesProcess($poseId, $username)
    {
        if (self::getModel()->postLikes($postId, $username) === 0)
            return '중복';
        else
            return '성공';
    }

    /**
     * 댓글 남길 때 요청
     */
    public static function postComment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $comment = $data->comment;
        $postId = $data->postId;
        $username = $data->username;

        self::getModel()->postComment($comment, $postId, $username);
        return;
    }

    /**
     * 게시물에 달린 댓글을 조회할 때 사용
     */
    public static function getCommentbyPostId($postId)
    {
        return self::getModel()->getCommentByPostId($postId);
    }

    /**
     * 마이페이지에서 내가 올린 게시물 확인할 때 사용
     */
    public static function getPostsByUsername($username)
    {
        return self::getModel()->getPostsByUsername($username);
    }

    /**
     * 마이페이지에서 닉네임 수정할 때 사용
     * [테스트]
     */
    public static function patchUsername()
    {
        $data = json_decode(file_get_contents("php://input"));
        $change = $data->username;
        $username = $_SESSION['username'];
        
        return print_r(self::patchUsernameProcess($change, $username));
    }
    public static function patchUsernameProcess($change, $username)
    {
        //유효성 검사();

        $result = self::getModel()->patchUsername($username, $change);
        if ($result === false)
            return '중복';
        else
        {
            $_SESSION['username'] = $change;
            return '성공';
        }
    }

    /**
     * 마이페이지에서 이메일 수정할 때 사용
     * [테스트]
     */
    public static function patchEmail()
    {
        $data = json_decode(file_get_contents("php://input"));
        $change = $data->email;
        $email = $_SESSION['email'];

        return (self::patchEmailProcess($change, $email));
    }
    public static function patchEmailProcess($change, $email)
    {
        //유효성 검사();

        $result = self::getModel()->patchEmail($email, $change);
        if ($result === false)
            return '중복';
        else
        {
            $_SESSION['email'] = $change;
            return '성공';
        }
    }

    /**
     * 마이페이지에서 비밀번호 수정할 때 사용
     * [테스트]
     */
    public static function patchPassword()
    {
        $data = json_decode(file_get_contents("php://input"));
        $originPassword = $data->originPassword;
        $newPassword = $data->newPassword;
        $checkPassword = $data->checkPassword;

        return print_r(self::patchPasswordProcess($originPassword, $newPassword, $checkPassword));
    }
    public static function patchPasswordProcess($originPassword, $newPassword, $checkPassword)
    {
        $username = $_SESSION['username'];

        if ($originPassword === $newPassword)
            return '현재 비밀번호와 같습니다.';
        else if ($newPassword !== $checkPassword)
            return '변경할 비밀번호와 비밀번호 확인이 일치하지 않습니다.';
        
        //유효성 검사();

        $result = self::getModel()->patchPassword($originPassword, $newPassword, $username);
        if ($result)
            return '성공';
        else
            return '기존 비밀번호가 틀렸습니다.';
    }

    /**
     * 로그아웃 시 세션 파괴
     * 추후 기능 구현에 따라 무언가 더 추가될 수도 있음
     */
    public static function postLogout()
    {
        session_destroy();
        return print_r('성공');
    }

    /**
     * 댓글 삭제 요청
     */
    public static function deleteComment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $commentId = $data->commentId;
        
        $result = self::getModel()->deleteComment($commentId);
        return print_r($result);
    }
    
    /**
     * 댓글 수정 요청
     */
    public static function patchComment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $commentId = $data->commentId;
        $newComment = $data->newComment;

        $result = self::getModel()->patchComment($commentId, $newComment);
        return print_r($result);
    }

    /**
     * 닉네임으로 userId찾기 주로 클라이언트에서 username으로 날아오는 데이터를 조회하기 편하게 하기 위해 사용
     */
    public static function getUserIdbyUsername($username)
    {
        return self::getModel()->getUserIdbyUsername($username);
    }
    
    /**
     * 게시물 삭제 요청
     */
    public static function deletePost()
    {
        $data = json_decode(file_get_contents("php://input"));

        $postId = $data->postId;

        $result = self::getModel()->deletePost($postId);
        return print_r($result);
    }

    /**
     * 이미지 삭제 요청. 이미지 파일 삭제도 이루어진다
     */
    public static function deleteImage()
    {
        $data = json_decode(file_get_contents("php://input"));

        $imageId = $data->imageId;
        $image = self::getModel()->getImageByImageId($imageId);
        unlink($image);

        $result = self::getModel()->deleteImage($imageId);
        return print_r($result);
    }

    /**
     * 마이페이지에서 좋아요 한 게시물을 확인할 때 사용
     */
    public static function getLikesPostsByUsername($username)
    {
        $result = self::getModel()->getLikesPostsByUsername($username);
        return $result;
    }
}

?>