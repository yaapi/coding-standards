<?php
$a = 1;
$b = 2;

// Fixable Errors
    // SpaceAfterKeyword
    if($a)
    {
        echo true;
    }

    try
    {
        $c = $a + $b;
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

    while($a < $b)
    {
        echo 'muh';
    }

    // @todo add keywords...


    // NewlineAfterOpenBrace
    if ($a)
    {

        echo true;
    }

    while ($a < $b)
    {


        echo 'muh';
    }

    // @todo add keywords...


    // SpaceBeforeSemicolon
    do
    {
        echo 'muh';
    }
    while ($a < $b) ;


    // SpaceAfterCloseBrace
    if ($a === 7)
    {
        echo 'muh';
    } 
    elseif ($b === 7)
    {
        echo 'muh';
    }


    // NewLineAfterCloseBrace
    if ($a === 7)
    {
        echo 'muh';
    }elseif ($b === 7)
    {
        echo 'muh';
    }


if   ($a === 2)
{
    echo 'muh';
}

if ($a === 2)
{ // blubb
    echo 'muh';
}

do
{
    echo 'muh';
}
while ($a < $b) 
;
