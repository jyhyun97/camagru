<link rel="stylesheet" type="text/css" href="app/styles/header.css">
<link rel="stylesheet" type="text/css" href="app/styles/modal.css">
<header class="navi">
    <button>홈</button>
    <button id="signin-button">로그인</button>
    <button id="signup-button">회원가입</button>
    <?php
    if (!isset($_SESSION['login']))
        echo "로그인 X";
    else
        echo "로그인 O";
    ?>
</header>
<?php
include_once 'app/view/modal.php';
?>
<script src="app/view/header.js" type="module"></script>
<script src="app/view/signin.js"></script>
<script src="app/view/signup.js"></script>