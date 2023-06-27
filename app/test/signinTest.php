<?php
include_once './test.php';
class SigninTest extends Test
{
    private function statusCode($function)
    {
        return http_response_code();
    }

    public function testSignin()
    {
        try
        {
            $this->assertEquals($this->statusCode(TestController::postSigninProcess('test@test.com', 'test1234')), 200);
            $this->assertEquals($this->statusCode(TestController::postSigninProcess('wrong@mail.com', 'test1234')), 401);
            $this->assertEquals($this->statusCode(TestController::postSigninProcess('test@test.com', 'wrongpass')), 401);
        } catch(Exception $e) {
            echo "$e\n";
            exit(1);
        }

    }
    public function run()
    {
        $startTime = microtime(true);
        $this->testSignin();
        $endTime = microtime(true);
        $time = number_format($endTime - $startTime, 6);
        echo $time."초 소요됨\n";
        exit(0);
    }
}

$test = new SigninTest();
$test->run();
?>