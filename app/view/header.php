<header class="navi">
    <button>홈</button>
    <button>로그인</button>
    <button>회원가입</button>
    <label>
        <?php
        include_once 'app/controller/mainController.php';
        $mainController = new MainController;

        echo $mainController->get_userId();
        ?>
    </label>
</header>