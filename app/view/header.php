<link rel="stylesheet" type="text/css" href="/app/styles/header.css">
<link rel="stylesheet" type="text/css" href="/app/styles/modal.css">
<header class="navi">
    <a href="/">
        <button>홈</button>
    </a>
    <?php
    if (!isset($_SESSION['login']) || $_SESSION['login'] == null)
    {
        echo "<button id='signin-button'>로그인</button>";
        echo "<button id='signup-button'>회원가입</button>";
        echo "<script src='/app/view/modal/signin.js' type='module'></script>";
        echo "<script src='/app/view/modal/signup.js' type='module'></script>";
    }
    else
    {
        echo "Hello, ".$_SESSION['login']."!!";
        echo "<a href='/mypage'><button id='mypage-button'>마이페이지</button></a>";
        echo "<button id='logout-button'>로그아웃</button>";
        echo "<script src='/app/view/logout.js'></script>";
    }
    ?>
</header>
<?php
include_once 'app/view/modal/modal.php';
?>