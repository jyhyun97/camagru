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
<script>
    const signinButton = document.getElementById('signin-button');
    signinButton.addEventListener('click', () => submitSignin('app/view/signin.php'));

    const signupButton = document.getElementById('signup-button');
    signupButton.addEventListener('click', () => submitSignin('app/view/signup.php'));

    function activeModal() {
        const modal = document.getElementById('modal');
        if (modal.style.visibility == 'visible')
            modal.style.visibility = 'hidden'
        else
            modal.style.visibility = 'visible';
    }

    function submitSignin(path) {
        const httpRequest = new XMLHttpRequest();

        httpRequest.onreadystatechange = alertContents;
        httpRequest.open('GET', path);
        httpRequest.send();
        activeModal();

        function alertContents() {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    const modalNode = document.getElementById('modal-content');
                    modalNode.innerHTML = httpRequest.responseText;
                } else {
                    alert('request 실패');
                }
            }
        }
    }
</script>