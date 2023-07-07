<?php
include_once 'app/router/router.php';
include_once 'app/controller/mainController.php';

session_start();

Router::add('/', 'GET', 'MainController::getMain');
Router::add('/post', 'GET', 'MainController::getPost');
Router::add('/mypage', 'GET', 'MainController::getMypage');
Router::add('/upload', 'GET', 'MainController::getUpload');
Router::add('/signup', 'POST', 'MainController::postSignup');
Router::add('/signup-auth', 'POST', 'MainController::postSignupAuth');
Router::add('/signin', 'POST', 'MainController::postSignin');
Router::add('/signin-auth', 'POST', 'MainController::postSigninAuth');
Router::add('/gallary', 'POST', 'MainController::postGallary');
Router::add('/capture', 'POST', 'MainController::postCapture');
Router::add('/image', 'POST', 'MainController::postImage');
Router::add('/likes', 'POST', 'MainController::postLikes');
Router::add('/comment', 'POST', 'MainController::postComment');
Router::add('/username', 'PATCH', 'MainController::patchUsername');
Router::add('/email', 'PATCH', 'MainController::patchEmail');
Router::add('/password', 'PATCH', 'MainController::patchPassword');
Router::add('/logout', 'POST', 'MainController::postLogout');
Router::add('/comment', 'DELETE', 'MainController::deleteComment');
Router::add('/comment', 'PATCH', 'MainController::patchComment');
Router::add('/post', 'DELETE', 'MainController::deletePost');
Router::add('/image', 'DELETE', 'MainController::deleteImage');
Router::add('/user', 'PATCH', 'MainController::patchUser');
Router::run();
?>