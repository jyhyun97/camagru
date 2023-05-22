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


    public static function get_main()
    {
        include_once 'app/view/main.php';
    }
    public static function get_post()
    {
        include_once 'app/view/post.php';
    }
    public static function get_mypage()
    {
        include_once 'app/view/mypage.php';
    }
    public static function get_upload()
    {
        include_once 'app/view/upload.php';
    }
    public static function get_signup()
    {
        include_once 'app/view/modal/signup.php';
    }
    public static function get_signin()
    {
        include_once 'app/view/modal/signin.php';
    }

    /**
     * signup 경로의 post 요청에 대해 유효성 검사 수행,
     * query 결과에 따라 중복된 이메일, 유저네임 알리기 
     */
    public static function post_signup()
    {
        $data = json_decode(file_get_contents("php://input"));

        $email = $data->email;
        $username = $data->username;
        $password = $data->password;
        if ($email == '' || $username == '' || $password == '')
            echo "빈 문자열입니다"; //추후 유효성 검사 더 넣기
        else {
            self::getModel()->post_signup($email, $username, $password);
            echo "성공!";
        }
        return;
    }
    /**
     * signin 경로의 post 요청에 대해 유효성 검사 수행,
     * query 결과에 따라 로그인 실패 이유, 로그인 성공 및 세션 저장 수행
     */
    public static function post_signin()
    {
        $data = json_decode(file_get_contents("php://input"));

        $email = $data->email;
        $password = $data->password;

        $result = self::getModel()->post_signin($email, $password);
        if ($result == null)
            echo "로그인 실패";
        else {
            echo "로그인 성공";
            $_SESSION['login'] = $result;
        }
        return;
        //성공 시 세션에 뭔가 저장해야 할 거 같은데...
    }
    public static function post_gallary()
    {
        $data = json_decode(file_get_contents("php://input"));

        $currentPage = $data->currentPage;
        $size = $data->size;
        $result = self::getModel()->post_gallary($currentPage, $size);

        // 마지막 페이지인지 판단
        if ($currentPage * $size > $result['rownum'])
            $result['lastPage'] = true;
        else
            $result['lastPage'] = false;
        echo json_encode($result);
        return;
    }
    public static function post_capture()
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
        $result = self::getModel()->post_capture($newFileName, $username);
        echo json_encode($result);
        return;
    }

    public static function getImagesByUsername($username)
    {
        return self::getModel()->getImagesByUsername($username);
    }

    public static function post_image()
    {
        $data = json_decode(file_get_contents("php://input"));

        $imageId = str_replace("captured-image-", "", $data->imageId);
        self::getModel()->post_image($imageId);
        return;
    }

    public static function getPostByPostId($postId)
    {
        return self::getModel()->getPostByPostId($postId);
    }

    public static function post_likes()
    {
        $data = json_decode(file_get_contents("php://input"));
        
        $postId = $data->postId;
        $username = $data->username;

        self::getModel()->post_likes($postId, $username);
        return;
    }

    public static function post_comment()
    {
        $data = json_decode(file_get_contents("php://input"));

        $comment = $data->comment;
        $postId = $data->postId;
        $username = $data->username;

        self::getModel()->post_comment($comment, $postId, $username);
        return;
    }
    public static function getCommentbyPostId($postId)
    {
        return self::getModel()->getCommentByPostId($postId);
    }
}

?>