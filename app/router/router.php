<?php

class Router
{
    private static $routes; //2차원 배열??

    // 경로, HTTP 메소드, 그에 맞게 실행될 콜백함수를 routes 변수에 등록
    public static function add($path, $method, $callback)
    {
        //routes[$path][$method]에 $callback 추가.
    }
    // 실행
    public static function run()
    {
        //$_SERVER 변수를 이용해 현재 경로에서 url, method 가져오기.
        //routes에서 해당하는 핸들러 찾아오기
        //핸들러가 없으면 경로 못찾는 경우 리턴

        //해당 함수 호출...
    }
// 나중에 잘못된 경로에 대한 예외처리를 만들어야함~~
}

?>