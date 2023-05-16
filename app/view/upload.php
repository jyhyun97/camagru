<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="app/styles/common.css">
    <link rel="stylesheet" type="text/css" href="app/styles/main.css">
    <link rel="stylesheet" type="text/css" href="app/styles/upload.css">
</head>

<body>
    <?php
    require_once('app/view/header.php');
    ?>
    <div class="content">
        <div class="main-content">
            <div id="upload-left">
                <div id="output">
                    <video id="video"></video>
                    <canvas id="canvas" width="640" height="480" hidden></canvas>
                    <img id="photo" hidden />
                </div>
                <div id="buttons">
                    <button id="capture-button">촬영</button>
                    <input type="file" accept="image/png, image/jpeg" id="upload-button" />
                </div>
                <div id="sticky-list">스티커 목록</div>
            </div>
            <div id="upload-right">
                <div id="captured-list">
                    생성한 이미지 목록
                </div>
                <button>업로드</button>
            </div>
        </div>
    </div>
    <script src="app/view/upload.js"></script>
</body>

</html>