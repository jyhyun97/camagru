<!-- 필요한 정보
로그인 여부
총 이미지 수, 한 번에 로드되는 이미지 수, 현재 로드된 이미지 수, 현재 페이지 번호
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="app/styles/common.css">
    <link rel="stylesheet" type="text/css" href="app/styles/main.css">
</head>

<body>
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
    <div class="content">
        <div class="gallary">
            <?php
            // foreach ($model->get_posts() as $ele) {
            //     echo "<img src=" . $ele . ">";
            // }
            ?>
        </div>
        <div class="buttons">
            <button>이전</button>
            <button>다음</button>
            <button>업로드</button>
        </div>
    </div>
</body>

</html>