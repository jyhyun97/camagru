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
        <img src="https://picsum.photos/600/300">
        <?php
        $currentUrl = $_SERVER['REQUEST_URI'];
        echo $currentUrl;
        // echo "<img src='imageId'>";
        ?>
        <label>좋아요 개수</label>
        <button>좋아요</button>
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


<!-- 
기본적으로 /post/postId로 갑시다...


 -->