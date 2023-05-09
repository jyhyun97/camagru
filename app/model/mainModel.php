<?php
class MainModel
{
    private $db_server;
    private $db_database;

    public function __construct()
    {
        require_once('app/module/login.php');

        $this->db_server = $db_server;
        $this->db_database = $db_database;
    }

    public function post_signup($email, $username, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $query = "INSERT INTO user (email, username, password, auth) VALUES ('$email', '$username', '$password', 'NULL')";
        $result = mysqli_query($this->db_server, $query);
        return '200 OK';
    }

    public function post_signin($email, $password)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $query = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($this->db_server, $query);
        $row = mysqli_fetch_assoc($result);
        return ($row['password'] == $password);
    }
}

?>