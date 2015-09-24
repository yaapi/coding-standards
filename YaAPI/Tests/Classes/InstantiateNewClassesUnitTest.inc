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
        $k = new self();
        $k = new self( );
        $k = new self ();

        return $this->value;
    }
}

$a = new MyClass();
$v = (new MyClass())->getValue();

$a = new MyClass( );
$v = (new MyClass( ))->getValue();

$a = new MyClass ();
$v = (new MyClass ())->getValue();

$a = new MyClass ( );
$v = (new MyClass ( ))->getValue();

$a = new MyClass(  );
$v = (new MyClass(  ))->getValue();

$a = new MyClass  ( );
$v = (new MyClass  ( ))->getValue();

// No errors should be returned from the following lines!
$a = new MyClass;
$a = new MyClass(null);
$a = new MyClass(false);
$a = new MyClass(true);
$a = new MyClass([]);
$a = new MyClass(['array_value']);
$a = new MyClass(['type' => 'array']);

// We don't allow old array style in our standard but we test it here.
$a = new MyClass(array());
