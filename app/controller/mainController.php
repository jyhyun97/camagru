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
    /**
     * signin 경로의 post 요청에 대해 유효성 검사 수행,
     * query 결과에 따라 로그인 실패 이유, 로그인 성공 및 세션 저장 수행
     */
    public static function post_signin()
    {
        $data = json_decode(file_get_contents("php://input"));
        $model = new MainModel;

        $email = $data->email;
        $password = $data->password;

        $result = $model->post_signin($email, $password);
        if ($result == false)
            echo "로그인 실패";
        else {
            echo "로그인 성공";
            $_SESSION['login'] = true;
        }
        return;
        //성공 시 세션에 뭔가 저장해야 할 거 같은데...
    }
}

?>