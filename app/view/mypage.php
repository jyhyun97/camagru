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
    if (!isset($_SESSION['username']))
        header('Location: /');
    ?>
    <div class="content">
        <div class="mypage-row row">
        <div class="mypage-change-form">
        <div>
            <lable>닉네임 : </lable>
            <?php
                echo "<label id='username-label'>".$_SESSION['username']."</label>";
            ?>
            <input id="username-change-input" hidden></input>
            <button id="username-change-button" >변경</button>
            <button id="username-submit-button" hidden>제출</button>
            <button id="username-cancel-button" hidden>취소</button>
        </div>
        <div>
            <lable>이메일 : </lable>
            <?php
                echo "<label id='email-label'>".$_SESSION['email']."</label>";
            ?>
            <input id="email-change-input" hidden></input>
            <button id="email-change-button">변경</button>
            <button id="email-submit-button" hidden>제출</button>
            <button id="email-cancel-button" hidden>취소</button>
        </div>
        <div>
            <lable>비밀번호 : </lable>
            <div id="password-origin" hidden>
                <lable id="password-origin-lable">기존 비밀번호</label>
                <input id="password-origin-input" type="password" hidden></input>
            </div>
            <div id="password-new" hidden>
                <lable id="password-new-lable">새 비밀번호</label>
                <input id="password-new-input" type="password" hidden></input>
            </div>
            <div id="password-check" hidden>
                <lable id="password-check-lable">비밀번호 확인</label>
                <input id="password-check-input" type="password" hidden></input>
            </div>
            <button id="password-change-button">변경</button>
            <button id="password-submit-button" hidden>제출</button>
            <button id="password-cancel-button" hidden>취소</button>
        </div>
        <button>메일 활성화/비활성화</button>
        <button>댓글 안내 메일 활성화/비활성화</button>
        </div>
        <label>내가 올린 이미지 목록</label>
        <div class="post-scroll">
            <?php
                $images = MainController::getImagesByUsername($_SESSION['username']);
                $imagesObj = new ArrayObject($images);
                $imagesIt = $imagesObj->getIterator();

                while ($imagesIt->valid())
                {
                    $ele = $imagesIt->current();
                    echo "<img src=".$ele['image']." width='200px'>";
                    echo "<button class='image-delete-button' data-image-id=".$ele['imageId'].">삭제</button>";
                    $imagesIt->next();
                }
            ?>
        </div>
        <label>내가 올린 게시물 목록</label>
        <div class="post-scroll">
            <?php
                $posts = MainController::getPostsByUsername($_SESSION['username']);
                $postsObj = new ArrayObject($posts);
                $postsIt = $postsObj->getIterator();

                while ($postsIt->valid())
                {
                    $ele = $postsIt->current();
                    echo "<a href=/post/".$ele['postId']."><img src=".$ele['image']." width='200px'></a>";
                    echo "<button class='post-delete-button' data-post-id=".$ele['postId'].">삭제</button>";
                    $postsIt->next();
                }
            ?>
        </div>
        <label>내가 좋아요한 게시물 목록</label>
        <div class="post-scroll">
            <?php
                $posts = MainController::getLikesPostsByUsername($_SESSION['username']);
                $postsObj = new ArrayObject($posts);
                $postsIt = $postsObj->getIterator();

                while ($postsIt->valid())
                {
                    $ele = $postsIt->current();
                    echo "<a href=/post/".$ele['postId']."><img src=".$ele['image']." width='200px'></a>";
                    $postsIt->next();
                }
            ?>
        </div>
        </div>
    </div>
</body>
<?php
    echo "<script src='/app/view/mypage.js' type='module'></script>";
?>
</html>