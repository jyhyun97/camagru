<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="/app/styles/common.css">
    <link rel="stylesheet" type="text/css" href="/app/styles/mypage.css">
</head>

<body>
    <?php
    require_once('app/view/header.php');
    ?>
    <div class="content">
        <div>
            <lable>닉네임 : </lable>
            <?php
                echo "<label id='username-label'>".$_SESSION['login']."</label>";
            ?>
            <input id="username-change-input" hidden></input>
            <button id="username-change-button">변경</button>
            <button id="username-submit-button" hidden>제출</button>
            <button id="username-cancel-button" hidden>취소</button>
        </div>
        <form>
            <lable>이메일 : </lable>
            <lable>aaaa@aaaa</lable>
            <button>변경</button>
        </form>
        <form>
            <lable>비밀번호 : </lable>
            <input type="text" disabled>
            <button>변경</button>
        </form>
        <button>메일 활성화/비활성화</button>
        <label>내가 올린 이미지 목록</label>
        <div id="myimages-scroll">
            <?php
                $images = mainController::getImagesByUsername($_SESSION['login']);
                foreach($images as $ele)
                    echo "<img src=".$ele['image']." width='200px'>";
            ?>
        </div>
        <label>내가 올린 게시물 목록</label>
        <div id="myposts-scroll">
            <?php
                $posts = mainController::getPostsByUsername($_SESSION['login']);
                foreach($posts as $ele)
                    echo "<a href=/post/".$ele['postId']."><img src=".$ele['image']." width='200px'></a>";
            ?>
        </div>
        <lable>내가 좋아요한 게시물 목록</label>
        <div id="mylikes-scroll"></div>
    </div>
</body>
 <script src="/app/view/mypage.js"></script>

</html>