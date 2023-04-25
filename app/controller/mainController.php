<?php
//모델 생성
//뷰 생성
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
}

?>