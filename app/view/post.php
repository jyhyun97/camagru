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
        $imageId = explode("/", $currentUrl)[2];

        $data = mainController::getPostByPostId($imageId);
        $likes = $data['likes'];
        $image = $data['image'];
        echo "<img src='../$image' width=200px height=200px>";
        echo "<label> 좋아요 $likes</label>";
        ?>
        <button id="likes-button">좋아요</button>
        <div>
            <label>댓글 작성자</label>
            <label>댓글 내용</label>
        </div>
        <form>
            <label>username</label>
            <input />
            <button>submit</button>
        </form>
    </div>
</body>
<script>
    const likesButton = document.getElementById("likes-button");
    likesButton.addEventListener("click", () => {
        const data = { postId: window.location.pathname.split('/')[2], username: 'jeonhyun' };
        const httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', '/likes');
        httpRequest.setRequestHeader('Content-Type', 'application/json');
        httpRequest.onload = () => {
            console.log(httpRequest.response);
            location.reload();
        };
        httpRequest.send(JSON.stringify(data));
    });
</script>