<?php
include_once 'app/model/mainModel.php';
class MainController
{
    private static $model = null;

    public static function getModel()
    {
        if (self::$model === null) {
            self::$model = new MainModel;
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
     * signup 경로의 post 요청에 대해 유효성 검사 수행,
     * query 결과에 따라 중복된 이메일, 유저네임 알리기 
     */
    public static function postSignup()
    {
        $data = json_decode(file_get_contents("php://input"));

        $email = $data->email;
        $username = $data->username;
        $password = $data->password;
        if ($email == '' || $username == '' || $password == '')
            echo "빈 문자열입니다"; //추후 유효성 검사 더 넣기
        else {
            self::getModel()->postSignup($email, $username, $password);
            echo "성공!";
        }
        return;
    }
    /**
     * signin 경로의 post 요청에 대해 유효성 검사 수행,
     * query 결과에 따라 로그인 실패 이유, 로그인 성공 및 세션 저장 수행
     */
    public static function postSignin()
    {
        $data = json_decode(file_get_contents("php://input"));

        $email = $data->email;
        $password = $data->password;

        $result = self::getModel()->postSignin($email, $password);
        if ($result == null)
            echo "로그인 실패";
        else {
            echo $result;
            $_SESSION['login'] = $result;
            $_SESSION['email'] = $email;
        }
        return;
    }

    public static function postGallary()
    {
        $data = json_decode(file_get_contents("php://input"));

        $currentPage = $data->currentPage;
        $size = $data->size;
        $result = self::getModel()->postGallary($currentPage, $size);

        // 마지막 페이지인지 판단
        if ($currentPage * $size > $result['rownum'])
            $result['lastPage'] = true;
        else
            $result['lastPage'] = false;
        echo json_encode($result);
        return;
    }
    public static function postCapture()
    {
        $data = json_decode(file_get_contents("php://input"));

        $username = $data->username;
        $baseImage = $data->baseImage;
        $stickyImages = $data->stickyImages;

        //합성();

        //파일 만들기
        $newFileName = "img/" . $username . "_" . date("Y-m-d_H:i:s") . ".png";
        $newImage = str_replace('data:image/png;base64,', '', $baseImage);
        $newImage = str_replace(' ', '+', $newImage);
        $newFile = fopen($newFileName, "w");
        fwrite($newFile, base64_decode($newImage));
        fclose($newFile);

        //db에 저장하기
        $result = self::getModel()->postCapture($newFileName, $username);
        echo json_encode($result);
        return;
    }

    public static function getImagesByUsername($username)
    {
        return self::getModel()->getImagesByUsername($username);
    }

    public static function postImage()
    {
        $data = json_decode(file_get_contents("php://input"));

        $imageId = str_replace("captured-image-", "", $data->imageId);
        self::getModel()->postImage($imageId);
        return;
    }

    public static function getPostByPostId($postId)
    {
        return self::getModel()->getPostByPostId($postId);
    }

    public static function postLikes()
    {
        $data = json_decode(file_get_contents("php://input"));
        
        $postId = $data->postId;
        $username = $data->username;

        self::getModel()->postLikes($postId, $username);
        return;
    }

    public static function postComment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $comment = $data->comment;
        $postId = $data->postId;
        $username = $data->username;

        self::getModel()->postComment($comment, $postId, $username);
        return;
    }
    public static function getCommentbyPostId($postId)
    {
        return self::getModel()->getCommentByPostId($postId);
    }
    public static function getPostsByUsername($username)
    {
        return self::getModel()->getPostsByUsername($username);
    }
    public static function patchUsername()
    {
        $data = json_decode(file_get_contents("php://input"));
        $change = $data->username;
        $username = $_SESSION['login'];
        
        //유효성 검사();

        $result = self::getModel()->patchUsername($username, $change);
        if ($result === false)
            return print_r('중복');
        else
        {
            $_SESSION['login'] = $change;
            return print_r('성공');
        }
    }
    public static function patchEmail()
    {
        $data = json_decode(file_get_contents("php://input"));
        $change = $data->email;
        $email = $_SESSION['email'];

        //유효성 검사();

        $result = self::getModel()->patchEmail($email, $change);
        if ($result === false)
            return print_r('중복');
        else
        {
            $_SESSION['email'] = $change;
            return print_r('성공');
        }
    }

    public static function patchPassword()
    {
        $data = json_decode(file_get_contents("php://input"));
        $originPassword = $data->originPassword;
        $newPassword = $data->newPassword;
        $checkPassword = $data->checkPassword;
        $username = $_SESSION['login'];

        if ($originPassword === $newPassword)
            return print_r('현재 비밀번호와 같습니다.');
        else if ($newPassword !== $checkPassword)
            return print_r('변경할 비밀번호와 비밀번호 확인이 일치하지 않습니다.');
        //유효성 검사();

        $result = self::getModel()->patchPassword($originPassword, $newPassword, $username);
        if ($result)
            return print_r('성공');  
        else
            return print_r('기존 비밀번호가 틀렸습니다.');
    }
}

?>