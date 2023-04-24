<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="./main.css">
</head>

<body>
    <header class="navi">
        <button>홈</button>
        <button>로그인</button>
        <button>회원가입</button>
    </header>
    <div class="content">
        <div class="main-content">
            <div id="upload-left">
                <canvas id="canvas"></canvas>
                <button>촬영</button>
                <button>이미지업로드</button>
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
</body>

</html>