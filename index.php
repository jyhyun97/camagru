<?php
include_once 'app/router/router.php';
include_once 'app/controller/mainController.php';

session_start();

Router::add('/', 'GET', 'MainController::getMain');
Router::add('/post', 'GET', 'MainController::getPost');
Router::add('/mypage', 'GET', 'MainController::getMypage');
Router::add('/upload', 'GET', 'MainController::getUpload');
Router::add('/signup', 'GET', 'MainController::getSignup');
Router::add('/signup', 'POST', 'MainController::postSignup');
Router::add('/signin', 'GET', 'MainController::getSignin');
Router::add('/signin', 'POST', 'MainController::postSignin');
Router::add('/gallary', 'POST', 'MainController::postGallary');
Router::add('/capture', 'POST', 'MainController::postCapture');
Router::add('/image', 'POST', 'MainController::postImage');
Router::add('/likes', 'POST', 'MainController::postLikes');
Router::add('/comment', 'POST', 'MainController::postComment');
Router::add('/username', 'PATCH', 'MainController::patchUsername');
Router::run();
?>