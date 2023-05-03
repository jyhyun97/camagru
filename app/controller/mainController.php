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

    public static function post_signup($data)
    {
        $model = new MainModel;

        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];
        //유효성 검사()
        //  return '에러코드, 메시지';
        $model->post_signup($email, $username, $password);
        return '회원가입 성공, 이메일 중복, 유효성검사 실패 등';
    }
}

?>