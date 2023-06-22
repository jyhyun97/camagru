<?php

class Router
{
    private static $routes = array();
    // 경로, HTTP 메소드, 그에 맞게 실행될 콜백함수를 routes 변수에 등록
    public static function add($path, $method = 'GET', $callback)
    {
        //아직 routes[$path]가 존재하지 않을 수 있는 경우 먼저 빈 배열 추가를 해야함
        if (!isset(self::$routes[$path])) {
            self::$routes[$path] = array();
        }
        self::$routes[$path][$method] = $callback;
    }
    // 실행

    //다른 곳에서도 쓸 것 같으면 module로 옮기고 여기서만 쓸 것 같으면 private 함수로 만들기
    static function pathParser($str)
    {
        $trimed = trim($str, "/");
        $pos = array();
        $pos['/'] = stripos($trimed, "/");
        $pos['?'] = stripos($trimed, "?");
        $pos['#'] = stripos($trimed, "#");

        if (array_filter($pos) == null)
            return ("/" . $trimed);
        else
            return ("/" . substr($trimed, 0, min(array_filter($pos))));
    }
    public static function run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $path = self::pathParser($url);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(self::$routes[$path][$method]))
            call_user_func(self::$routes[$path][$method]);
        else
        {
            http_response_code(404);
            include_once('app/view/404.php');
        }
    }
}

?>