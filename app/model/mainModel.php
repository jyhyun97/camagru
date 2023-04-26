<?php
class MainModel
{
    private $user;
    private $posts = array();

    public function __construct()
    {
        require_once('app/module/login.php');

        mysqli_select_db($db_server, $db_database);
        $query = "SELECT * FROM user WHERE username = 'jeonhyun'";
        $result = mysqli_query($db_server, $query);
        $row = mysqli_fetch_array($result);
        $this->user = $row['username'];
        for ($i = 0; $i < 5; $i++) {
            $this->posts[$i] = "https://picsum.photos/300/200";
        }
    }
    public function get_user()
    {
        return $this->user;
    }
    public function get_posts()
    {
        return $this->posts;
    }
}

?>