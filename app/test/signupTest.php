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
                'ArthurDent42@hitchhiker.galaxy'));
            $this->assertTrue(TestController::validateEmail(
                'F.Mulder.X-files@fbi.gov'));
            $this->assertTrue(TestController::validateEmail(
                'D.Scully.X-files@fbi.gov'));
            $this->assertFalse(TestController::validateEmail(
                'ItsOver@Anakin'));
            $this->assertFalse(TestController::validateEmail(
                'I!Have!!@the.high.ground'));
            $this->assertFalse(TestController::validateEmail(
                ''));

            $this->assertTrue(TestController::validateUsername(
                'JamesKirk1701'));
            $this->assertTrue(TestController::validateUsername(
                'USSenterprise'));
            $this->assertFalse(TestController::validateUsername(
                'SPACEeEeEeEeeeeeEEeee'));
            $this->assertFalse(TestController::validateUsername(
                'ImInSpace!!'));
            $this->assertFalse(TestController::validateUsername(
                ''));

            $this->assertTrue(TestController::validatePassword(
                'passwordSimple'));
            $this->assertTrue(TestController::validatePassword(
                'PaS!Wor@s$mpl^'));
            $this->assertTrue(TestController::validatePassword(
                'deepSpace9'));
            $this->assertTrue(TestController::validatePassword(
                'ncc!@74656'));
            $this->assertFalse(TestController::validatePassword(
                '42'));
            $this->assertFalse(TestController::validatePassword(
                'overThe20LetterCase!!!!'));
            $this->assertFalse(TestController::validatePassword(
                ''));
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