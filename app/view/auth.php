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
    <link rel="shortcut icon" href="/app/asset/favicon.ico">
</head>

<body>
    <?php
    $authcode = explode("=", strip_tags($_SERVER['REQUEST_URI']));

    if (isset($authcode) && $authcode[0] === "/auth?authcode" && isset($authcode[1])) {
        $auth = self::getModel()->getAuthInfo($authcode[1])['data'];
        if ($auth === null) {
            echo "<div>인증에 실패했습니다. url을 확인해주세요.</div>";
            echo "<a href='/'><button>메인으로 돌아가기</button></a>";
        } else if ($auth['authType'] === 'signup') {
            self::getModel()->postSignup($auth['email'], $auth['username'], $auth['password']);
            self::getModel()->deleteAuthInfo($auth['authCode']);
            echo "<div>인증에 성공했습니다. 메인으로 돌아가 로그인을 진행해주세요.</div>";
            echo "<a href='/'><button>메인으로 돌아가기</button></a>";
        } else if ($auth['authType'] === 'signin') {
            $user = self::getModel()->getUserbyEmail($auth['email']);
            $_SESSION['username'] = $user['data']['username'];
            $_SESSION['email'] = $user['data']['email'];
            self::getModel()->deleteAuthInfo($auth['authCode']);
            echo "<div>인증에 성공했습니다. 메인화면으로 돌아갑니다.</div>";
            header('Location: /');
        }
    } else {
        echo "<div>인증에 실패했습니다. url을 확인해주세요.</div>";
        echo "<a href='/'><button>메인으로 돌아가기</button></a>";
    }
    ?>
</body>

</html>
<?php
ob_end_flush();
?>