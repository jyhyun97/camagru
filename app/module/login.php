<?php
$env = parse_ini_file('.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

$db_hostname = getenv('DB_HOST');
$db_database = getenv('DB_DATABASE');
$db_user = getenv('DB_USER');
$db_password = getenv('DB_PASSWORD');

$db_server = mysqli_connect($db_hostname, $db_user, $db_password, $db_database);
?>