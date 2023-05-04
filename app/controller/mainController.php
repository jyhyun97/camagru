<?php
include_once 'app/model/mainModel.php';
class MainController
{
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
        include_once 'app/view/signup.php';
    }
    public static function get_signin()
    {
        include_once 'app/view/signin.php';
    }

    public static function post_signup()
    {
        $data = json_decode(file_get_contents("php://input"));
        $model = new MainModel;

        $email = $data->email;
        $username = $data->username;
        $password = $data->password;
        if ($email == '' || $username == '' || $password == '')
            echo "빈 문자열입니다"; //추후 유효성 검사 더 넣기
        else {
            $model->post_signup($email, $username, $password);
            echo "성공!";
        }
        return;
    }
}

?>