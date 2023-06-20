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
        <div class="row" id="post-container">
        <?php
        $currentUrl = $_SERVER['REQUEST_URI'];
        $postId = explode("/", $currentUrl)[2];

        $data = MainController::getPostByPostId($postId);
        $likes = $data['likes_count'];
        $image = $data['image'];
        $userId = $data['userId'];
        
        echo "<div class='text-center'><img src='../$image' id='post-image' class='img-responsive'></div>";
        echo "<div id='post-buttons' class='text-center'>";
        echo "<button id='likes-button' class='btn btn-default'>👍 $likes</button>";
        if ($userId === MainController::getUserIdbyUsername($_SESSION['username']))
            echo "<button id='post-delete-button' class='btn btn-default'>게시물 삭제</button>";
        echo "</div>";
        ?>
        <ul class="comment">
            <?php
            $currentUrl = $_SERVER['REQUEST_URI'];
            $postId = explode("/", $currentUrl)[2];
            $data = MainController::getCommentbyPostId($postId);

            foreach($data as $ele)
            {
                echo "<li class='text-left list-unstyled container'>";
                echo "<span class='col-md-1'><strong>".$ele['username']."</strong></span>";
                echo "<span class='col-md-7'>".$ele['comment'];
                if ($ele['username'] === $_SESSION['username'])
                    echo "<textarea data-comment-id='".$ele['commentId']."' class='comment-patch-input' hidden></textarea>";
                echo "</span>";
                echo "<span class='col-md-2'>".$ele['date']."</span>";
                echo "<span class='col-md-2'>";
                if ($ele['username'] === $_SESSION['username'])
                {
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-patch-button'>변경</button>";
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-patch-submit' hidden>제출</button>";
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-patch-cancel' hidden>취소</button>";
                    echo "<button data-comment-id='".$ele['commentId']."' class='comment-delete-button'>삭제</button>";
                }    
                echo "</span>";
                echo "</li>";
            }
            echo "<script src='/app/view/post.js' type='module'></script>";
            ?>
        </ul>
        <form id='comment-form' class="form-inline">
            <?php
            if (isset($_SESSION['username'])) {
                echo "<div class='form-group'><label id='login-label'>" . $_SESSION['username'] . "</label>";
                echo "<div class='input-group'><textarea id='comment-input' class='form-control'></textarea></div>";
                echo "<button id='comment-submit-button' class='btn btn-primary'>submit</button></div>";
                echo "<script src='/app/view/comment.js'></script>";
            } else
                echo "<label>로그인 한 사용자만 댓글을 달 수 있습니다</label>";
            ?>
        </form>
        </div>
    </div>
</body>
