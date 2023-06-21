<link rel="stylesheet" href="/app/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/app/styles/modal.css">

<nav class="navbar navbar-default">
    <button class="btn btn-default navbar-btn" onclick="location.href='/'">홈</button>
    <?php
    if (!isset($_SESSION['username']) || $_SESSION['username'] == null)
    {
        echo "<button class='btn btn-default navbar-btn' id='signin-button'>로그인</button>";
        echo "<button class='btn btn-default navbar-btn' id='signup-button'>회원가입</button>";
        echo "<script src='/app/view/modal/signin.js' type='module'></script>";
        echo "<script src='/app/view/modal/signup.js' type='module'></script>";
    }
    else
    {
        echo "Hello, ".$_SESSION['username']."!!";
        echo "<a href='/mypage'><button id='mypage-button' class='btn btn-default navbar-btn'>마이페이지</button></a>";
        echo "<button id='logout-button' class='btn btn-default navbar-btn'>로그아웃</button>";
        echo "<script src='/app/view/logout.js'></script>";
    }
    ?>
</nav>
<?php
    include_once 'app/view/modal/modal.php';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/app/bootstrap/js/bootstrap.min.js"></script>
