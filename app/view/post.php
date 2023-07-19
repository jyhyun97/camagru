<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="/app/styles/common.css">
    <link rel="stylesheet" type="text/css" href="/app/styles/post.css">
    <link rel="shortcut icon" href="/app/asset/favicon.ico">
</head>

<?php
if (isset($_SESSION['username'])) {
    $user = MainController::getUserbyUsername($_SESSION['username']);
    $auth = $user['auth'];

    if ($auth === 'temporal')
        header('Location: /mypage');
}
?>

<body>
    <?php
    require_once('app/view/header.php');
    ?>
    <div class="content">
        <div class="wrapper">
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
                if (isset($_SESSION['username']) && $userId === MainController::getUserIdbyUsername($_SESSION['username']))
                    echo "<button id='post-delete-button' class='btn btn-default'>게시물 삭제</button>";
                echo "</div>";
                ?>
                <ul class="comment">
                    <?php
                    $currentUrl = $_SERVER['REQUEST_URI'];
                    $postId = explode("/", $currentUrl)[2];
                    $data = MainController::getCommentbyPostId($postId);
                    $dataObj = new ArrayObject($data);
                    $dataIt = $dataObj->getIterator();

                    while ($dataIt->valid()) {
                        $ele = $dataIt->current();
                        echo "<li class='text-left list-unstyled container'>";
                        echo "<span class='col-md-1 col-sm-1 col-xs-3'><strong>" . $ele['username'] . "</strong></span>";
                        echo "<span class='col-md-7 col-sm-8 col-xs-9'>" . $ele['comment'];
                        if (isset($_SESSION['username']) && $ele['username'] === $_SESSION['username'])
                            echo "<textarea data-comment-id='" . $ele['commentId'] . "' class='comment-patch-input hidden'></textarea>";
                        echo "</span>";
                        $date = date_format(date_create($ele['date']), "y/m/d h:i:s");
                        echo "<span class='col-md-2 col-sm-1  col-xs-3'>" . $date . "</span>";
                        echo "<span class='col-md-2 col-sm-2  col-xs-3'>";
                        if (isset($_SESSION['username']) && $ele['username'] === $_SESSION['username']) {
                            echo "<button data-comment-id='" . $ele['commentId'] . "' class='comment-patch-button btn btn-default'>변경</button>";
                            echo "<button data-comment-id='" . $ele['commentId'] . "' class='comment-patch-submit btn btn-default hidden'>제출</button>";
                            echo "<button data-comment-id='" . $ele['commentId'] . "' class='comment-patch-cancel btn btn-default hidden'>취소</button>";
                            echo "<button data-comment-id='" . $ele['commentId'] . "' class='comment-delete-button btn btn-default'>삭제</button>";
                        }
                        echo "</span>";
                        echo "</li>";
                        $dataIt->next();
                    }
                    echo "<script src='/app/view/post.js' type='module'></script>";
                    ?>
                </ul>
                <form id='comment-form' class="form-inline">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo "<div class='form-group row'>";
                        echo "<div class='col-md-1 col-sm-2 col-xs-3'><label id='login-label'>" . $_SESSION['username'] . "</label></div>";
                        echo "<div class='col-md-10 col-sm-8 col-xs-12'><textarea id='comment-input' class='form-control'></textarea></div>";
                        echo "<div class='col-md-1 col-sm-2 col-xs-3'><button id='comment-submit-button' class='btn btn-primary'>submit</button></div>";
                        echo "</div>";
                        echo "<script src='/app/view/comment.js'></script>";
                    } else
                        echo "<label>로그인 한 사용자만 댓글을 달 수 있습니다</label>";
                    ?>
                </form>
            </div>
        </div>
    </div>
    <?php
    require_once('app/view/footer.php');
    ?>
</body>
<?php
ob_end_flush();
?>