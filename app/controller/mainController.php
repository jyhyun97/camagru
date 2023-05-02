<?php
include_once 'app/model/mainModel.php';
class MainController
{
    private $model;

    public function __construct()
    {
        $this->model = new MainModel;
    }

    public function get_userId()
    {
        return "<label> hello " . $this->model->get_user() . "</label>";
    }

    public function post_signup($data)
    {

        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];
        //유효성 검사()
        //  return '에러코드, 메시지';
        $this->model->post_signup($email, $username, $password);
        return '회원가입 성공, 이메일 중복, 유효성검사 실패 등';
    }
}

?>