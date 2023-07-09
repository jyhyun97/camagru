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
    <link rel="stylesheet" type="text/css" href="app/styles/common.css">
    <link rel="stylesheet" type="text/css" href="app/styles/upload.css">
</head>

<body>
    <?php
        require_once('app/view/header.php');
        if (!isset($_SESSION['username'])) {
            header('Location: /');
        } else if (isset($_SESSION['username'])) {
            $user = MainController::getUserbyUsername($_SESSION['username']);
            $auth = $user['auth'];
    
            if ($auth === 'temporal')
                header('Location: /mypage');
        }
    ?>
    <div class="content">
        <div class="main-content">
            <div id="upload-left">
                <div id="output">
                    <canvas id='sticky-canvas' width="640" height="480"></canvas>
                    <video id="video"></video>
                    <canvas id="canvas" width="640" height="480" hidden></canvas>
                    <img id="photo" hidden />
                </div>
                <div id="buttons">
                    <input type="file" accept="image/png, image/jpeg" id="upload-button"></input>    
                    <button id="capture-button" class='btn btn-primary'>촬영</button>    
                </div>
                <div id="sticky-list">
                    스티커 목록
                    <?php
                        foreach (new DirectoryIterator('img/sticky/') as $fileInfo)
                        {
                            $filePath = $fileInfo->getPathname();
                            if ($fileInfo->getFileName() === '.' || $fileInfo->getFileName() === '..')
                                continue;
                            echo "<img src='$filePath' class='sticky-image' id='$filePath' onclick='selectSticky(event)'>";
                        }
                    ?>
                </div>
            </div>
            <div id="upload-right">
                <div id="captured-list">
                    생성한 이미지 목록
                    <?php
                    $username = $_SESSION['username'];
                    $imagesObj = new ArrayObject(Maincontroller::getImagesByUsername($username));
                    $imagesIt = $imagesObj->getIterator();

                    while ($imagesIt->valid())
                    {
                        $src = $imagesIt->current()['image'];
                        $imageId = $imagesIt->current()['imageId'];

                        echo "<div class='capture'>";
                        echo "<img src=\"$src\" class='captured-image' onclick='selectImage(event)' id='captured-image-$imageId'>";
                        echo "<button onclick='deleteImage(event)' class='capture-delete-button' data-image-id='$imageId'>삭제</button>";
                        echo "</div>";
                        
                        $imagesIt->next();
                    }
                    ?>
                </div>
                <button id="post-button" class='btn btn-primary' disabled>업로드</button>
            </div>
        </div>
    </div>
    <script src="/app/view/upload.js"></script>
    <?php
        require_once('app/view/footer.php');
    ?>
</body>
</html>
<?php
    ob_end_flush(); 
?>