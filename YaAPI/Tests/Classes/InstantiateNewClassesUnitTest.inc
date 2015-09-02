<?php
class MyClass
{
    protected $value = 'value-default';
    
    public function __construct($value = null)
    {
        if (null !== $value)
        {
            $this->value = $value;
        }
    }
    
    public function getValue()
    {
        return $this->value;
    }
}

// Should throw an error.
$a = new MyClass();

// Should be fine.
$b = new MyClass('new-value');

// Should throw an error.
$a = (new MyClass())->getValue();

// Should be fine.
$b = (new MyClass('new-value'))->getValue();