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
            echo "testPlus success!\n";
        } catch (Exception $e) {
            echo "$e\n";
        }
    }

    public function run()
    {
        $this->testDup();
    }
}



$test1 = new SignupTest;
$test1->run();
?>