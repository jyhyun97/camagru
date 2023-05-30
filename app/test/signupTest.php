<?php
include_once './test.php';
class SignupTest extends Test
{
    public function testDup()
    {
        try {
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