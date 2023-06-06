<?php
include_once './test.php';
class SignupTest extends Test
{
    public function testDup()
    {
        try {
            $this->assertEquals(TestController::postSignupProcess(
                'abcd@1234', 'bb', 'abcd1234'), '중복된 닉네임입니다.');
            //테스트 작성
            print_r("testPlus success!\n");
        } catch (Exception $e) {
            echo "$e\n";
        }
    }
    public function testValidate()
    {
        try {
            $this->assertTrue(TestController::validateEmail(
                'aa@bb.com'));
            $this->assertFalse(TestController::validateEmail(
                'abcd.com'));
            print_r("testValidate success!\n");
        } catch (Exception $e) {
            echo "$e\n";
        }
    }

    public function run()
    {
        $startTime = microtime(true);
        $this->testDup();
        $this->testValidate();
        $endTime = microtime(true);
        $time = number_format($endTime - $startTime, 6);
        echo $time."초 소요됨\n";
    }
}



$test1 = new SignupTest;
$test1->run();
?>