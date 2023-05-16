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

    public function post_gallary($currentPage, $size)
    {
        mysqli_select_db($this->db_server, $this->db_database);
        $currentRow = ($currentPage - 1) * $size;
        //post와 image를 imageId 기준으로 inner join하고, 생성순으로 내림차순 정렬해서
        //페이지와 사이즈에 해당하는 개수의 데이터를 불러오는 쿼리문
        $query = "SELECT * FROM post JOIN image ON post.imageId = image.imageId
        ORDER BY postId DESC LIMIT $currentRow, $size";
        $result = mysqli_query($this->db_server, $query);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $rownum_query = "SELECT * FROM post";
        $rownum_result = mysqli_query($this->db_server, $rownum_query);
        $rownum = mysqli_num_rows($rownum_result);

        $response = array();
        $response['rownum'] = $rownum;
        $response['data'] = $row;
        return $response;
    }
    public function post_capture($image, $username)
    {
        mysqli_select_db($this->db_server, $this->db_database);

        $userIdQuery = "SELECT * FROM user WHERE username = '$username'";
        $userIdResult = mysqli_query($this->db_server, $userIdQuery);
        $userId = mysqli_fetch_array($userIdResult, MYSQLI_NUM)[0];

        $insertQuery = "INSERT INTO image (date, image, userId) VALUES (NOW(), '$image', '$userId[0]')";
        $insertResult = mysqli_query($this->db_server, $insertQuery);
        return;
    }
}

?>