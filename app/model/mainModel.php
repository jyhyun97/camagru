<?php
class MainModel
{
    private $user;
    private $posts = array();

    private $db_server;
    private $db_database;

    public function __construct()
    {
        require_once('app/module/login.php');

        $this->db_server = $db_server;
        $this->db_database = $db_database;
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
    public function post_signup($email, $username, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $query = "INSERT INTO user (email, username, password, auth) VALUES ('$email', '$username', '$password', 'NULL')";
        $result = mysqli_query($this->db_server, $query);
        return '200 OK'; //나중에 제대로 하세요...
    }
}

?>