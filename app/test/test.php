<?php

$path = rtrim(__DIR__, '/app/test');
chdir($path);

include_once 'app/controller/mainController.php';
class TestController extends MainController
{
    private static $model = null;

    public static function getModel()
    {
        if (self::$model === null) {
            self::$model = new MainModel('test');
        }
        return self::$model;
    }
}

abstract class Test
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


?>