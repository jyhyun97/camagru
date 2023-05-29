<?php

function plus($a, $b)
{
return ($a + $b);
}

class plusTest extends test
{
    public function testPlus()
    {
        try {
            $this->assertEquals(plus(1, 3), 4);
            $this->assertEquals(plus(2, 3), 5);
            echo "testPlus success!\n";
        } catch (Exception $e) {
            echo "$e\n";
        }
    }

    public function run()
    {
        $this->testplus();
    }
}

abstract class test
{
    public abstract function run();
    
    public function assertEquals($actual, $expect)
    {
        if ($actual !== $expect) {
            throw new Exception("actual : [$actual], expect : [$expect]");
        }
    }
    
    public function assertTrue($actual)
    {
        if (!$actual) {
            throw new Exception("actual : [$actual], expect : true");
        }
    }
    
    public function assertFalse($actual)
    {
        if ($actual) {
            throw new Exception("actual : [$actual], expect : false");
        }
    }
    
    public function assertEmpty($actual)
    {
        if (!empty($actual)) {
            throw new Exception("actual : [$actual], expect : empty");
        }
    }
}

$test1 = new plusTest;
$test1->run();

?>