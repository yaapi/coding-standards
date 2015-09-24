<?php
class MyClass
{
    public function __yeahHaey()
    {
        return;
    }

    private function _coolPrivate()
    {
        return;
    }

    public function cool_name()
    {
        return;
    }

    public function coolName_Function()
    {
        return;
    }

    function _noPrivate()
    {
        return;
    }
}

echo preg_replace_callback('/-([a-z])/', function ($m) { return strtoupper($m[1]); }, 'my-class');

class myClass
{
    public function myClass()
    {
        
    }

    public function _myClass()
    {
    
    }
}
