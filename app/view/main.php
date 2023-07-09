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
    <link rel="stylesheet" type="text/css" href="app/styles/main.css">
    <link rel="shortcut icon" href="/app/asset/favicon.ico">
</head>

<?php
    if (isset($_SESSION['username']))
    {
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
        <?php
        require_once('app/view/gallary.php');
        ?>
        <div class="buttons">
            <a href="/upload"><button class="btn btn-default">업로드</button></a>
        </div>
        </div>
    </div>
    <?php
        require_once('app/view/footer.php');
    ?>
</body>

</html>
<?php
    ob_end_flush(); 
?>