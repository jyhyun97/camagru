<?php
include_once './test.php';
class InjectionTest extends Test
{
    private function statusCode($function)
    {
        return http_response_code();
    }

    private function SQLinjectionTest()
    {
        try {
            $this->assertEquals($this->statusCode(TestController::postSigninProcess('jeonhyun@student.42seoul.kr', ' OR 1=1 -- ')), 400);
            $this->assertEquals($this->statusCode(TestController::patchUsernameProcess(' OR 1=1 -- ', 'jeonhyun')), 400);
            $_SERVER['REQUEST_URI'] = '/post/<script>alert("url injection!")</script>';
            $this->assertEquals($this->statusCode(TestController::getPost()), 404);
        } catch (Exception $e) {
            echo "$e\n";
            exit(1);
        }
    }
    public function run()
    {
        $startTime = microtime(true);
        $this->SQLinjectionTest();

        $endTime = microtime(true);
        $time = number_format($endTime - $startTime, 6);
        echo $time . "초 소요됨\n";
        exit(0);
    }
}
$test = new InjectionTest;
$test->run();
?>