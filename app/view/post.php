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

        $data = MainController::getPostByPostId($postId);
        $likes = $data['likes'];
        $image = $data['image'];
        $userId = $data['userId'];
        
        if ($userId === MainController::getUserIdbyUsername($_SESSION['username']))
            echo "<button id='post-delete-button'>게시물 삭제</button>";
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
                echo "<div class='comment'>";//추후 <li>로 변경
                echo "<span class='comment-username'>".$ele['username']."</span>";
                echo "<span class='comment-comment'>".$ele['comment']."</span>";
                echo "<span class='comment-date'>".$ele['date']."</span>";
                if ($ele['username'] === $_SESSION['username'])
                {
                    echo "<input data-comment-id='".$ele['commentId']."' class='comment-patch-input' hidden></input>";
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-patch-button'>변경</button>";
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-patch-submit' hidden>제출</button>";
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-patch-cancel' hidden>취소</button>";
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-delete-button'>삭제</button>";
                }    
                echo "</div>";
            }
            echo "<script src='/app/view/post.js' type='module'></script>";
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
