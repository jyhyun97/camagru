<?php
class MainModel
{
    private $user;
    private $posts = array();

    public function __construct()
    {
        $this->user = "임시 유저 이름";
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