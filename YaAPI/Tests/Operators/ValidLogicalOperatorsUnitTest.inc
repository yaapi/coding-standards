<?php
$a = 1;
$b = 2;
$c = 3;

if ($a and $b)
{
    echo $a + $b;
}

if ($a or $b)
{
    echo $c;
}

if ($a and $c or $b)
{
    echo 6;
}

if ($x = $a or $c === 3
    and $b === 2
)
{
    echo 'muh';
}
