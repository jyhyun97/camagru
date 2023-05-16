<?php
include_once 'app/router/router.php';
include_once 'app/controller/mainController.php';

session_start();

Router::add('/', 'GET', 'MainController::get_main');
Router::add('/post', 'GET', 'MainController::get_post');
Router::add('/mypage', 'GET', 'MainController::get_mypage');
Router::add('/upload', 'GET', 'MainController::get_upload');
Router::add('/signup', 'GET', 'MainController::get_signup');
Router::add('/signup', 'POST', 'MainController::post_signup');
Router::add('/signin', 'GET', 'MainController::get_signin');
Router::add('/signin', 'POST', 'MainController::post_signin');
Router::add('/gallary', 'POST', 'MainController::post_gallary');
Router::add('/capture', 'POST', 'MainController::post_capture');
Router::add('/image', 'POST', 'MainController::post_image');
Router::run();
?>