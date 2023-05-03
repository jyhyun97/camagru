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
    public static function run()
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(self::$routes[$path][$method]))
            call_user_func(self::$routes[$path][$method]);
        //arge 필요?? 나중에 생각해서 처리하자..
        else
            echo '라우터에 등록된 요청인지 확인하세요';
    }
// 나중에 잘못된 경로에 대한 예외처리를 만들기.
}

?>