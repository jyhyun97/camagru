<?php
include_once './test.php';
class ValidateTest extends Test
{
    private function usernameTest()
    {
        try {
            //일반
            $this->assertTrue(TestController::validateUsername('abcde'));
            $this->assertTrue(TestController::validateUsername('cadet42'));
            $this->assertTrue(TestController::validateUsername('todayIsRainyDay'));
            //실패
            $this->assertFalse(TestController::validateUsername(''));
            $this->assertFalse(TestController::validateUsername('123'));
            $this->assertFalse(TestController::validateUsername('한글'));
            $this->assertFalse(TestController::validateUsername('include space'));
            $this->assertFalse(TestController::validateUsername('overthe20letterssssssss'));
        } catch(Exception $e) {
            echo "$e\n";
            exit(1);
        }
    }
    private function emailTest()
    {
        try {
            //일반
            $this->assertTrue(TestController::validateEmail(
                'ArthurDent42@hitchhiker.galaxy'));
            $this->assertTrue(TestController::validateEmail(
                'F.Mulder.X-files@fbi.gov'));
            $this->assertTrue(TestController::validateEmail(
                'D.Scully.X-files@fbi.gov'));
                
            //실패
            $this->assertFalse(TestController::validateEmail(
                'ItsOver@Anakin'));
            $this->assertFalse(TestController::validateEmail(
                'I!Have!!@the.high.ground'));
            $this->assertFalse(TestController::validateEmail(
                ''));
        } catch(Exception $e) {
            echo "$e\n";
            exit(1);
        }
    }
    private function passwordTest()
    {
        try {
            //일반
            $this->assertTrue(TestController::validatePassword(
                'passwordSimple'));
            $this->assertTrue(TestController::validatePassword(
                'PaS!Wor@s$mpl^'));
            $this->assertTrue(TestController::validatePassword(
                'deepSpace9'));
            $this->assertTrue(TestController::validatePassword(
                'ncc!@74656'));
            //실패
            $this->assertFalse(TestController::validatePassword(
                '42'));
            $this->assertFalse(TestController::validatePassword(
                'overThe20LetterCase!!!!'));
            $this->assertFalse(TestController::validatePassword(
                ''));
            $this->assertFalse(TestController::validatePassword(
                'itstosimple'));
        } catch(Exception $e) {
            echo "$e\n";
            exit(1);
        }
    }
    public function run()
    {
        $startTime = microtime(true);
        $this->usernameTest();
        $this->emailTest();
        $this->passwordTest();
        $endTime = microtime(true);
        $time = number_format($endTime - $startTime, 6);
        echo $time."초 소요됨\n";
        exit(0);
    }
}
$test = new ValidateTest;
$test->run();
?>