<link rel="stylesheet" type="text/css" href="app/styles/header.css">
<link rel="stylesheet" type="text/css" href="app/styles/modal.css">
<header class="navi">
    <button>홈</button>
    <button id="signin-button">로그인</button>
    <button id="signup-button">회원가입</button>
    <label>
        <?php
        include_once 'app/controller/mainController.php';
        $mainController = new MainController;
        echo $mainController->get_userId();
        ?>
    </label>
</header>
<?php
include_once 'app/view/modal.php';
?>
<script src="app/view/header.js" type="module"></script>