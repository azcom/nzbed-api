--TEST--
Function -- vsprintf
--FILE--
<?php
require_once 'PHP/Compat/Function/vsprintf.php';

$values = array (2, 'car');

$format = "There are %d monkeys in the %s";
echo php_compat_vsprintf($format, $values);
?>
--EXPECT--
There are 2 monkeys in the car