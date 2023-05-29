<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="/app/styles/common.css">
    <link rel="stylesheet" type="text/css" href="/app/styles/post.css">
</head>

<body>
    <?php
    require_once('app/view/header.php');
    ?>
    <div class="content">
        <?php
        $currentUrl = $_SERVER['REQUEST_URI'];
        $postId = explode("/", $currentUrl)[2];

        $data = mainController::getPostByPostId($postId);
        $likes = $data['likes'];
        $image = $data['image'];
        echo "<img src='../$image' width=200px height=200px>";
        echo "<label> 좋아요 $likes</label>";
        ?>
        <button id="likes-button">좋아요</button>
        <div>
            <?php
            $currentUrl = $_SERVER['REQUEST_URI'];
            $postId = explode("/", $currentUrl)[2];
            $data = MainController::getCommentbyPostId($postId);

            foreach($data as $ele)
            {
                echo "<label class='comment-username'>".$ele['username']."</label>";
                echo "<label class='comment-comment'>".$ele['comment']."</label>";
                echo "<label class='comment-date'>".$ele['date']."</label>";                
            }
            ?>
        </div>
        <form>
            <?php
            if (isset($_SESSION['username'])) {
                echo "<label id='login-label'>" . $_SESSION['username'] . "</label>";
                echo "<input id='comment-input'/>";
                echo "<button id='comment-submit-button'>submit</button>";
                echo "<script src='/app/view/comment.js'></script>";
            } else
                echo "<label>로그인 한 사용자만 댓글을 달 수 있습니다</label>";
            ?>
        </form>
    </div>
</body>
<script src="/app/view/post.js"></script>