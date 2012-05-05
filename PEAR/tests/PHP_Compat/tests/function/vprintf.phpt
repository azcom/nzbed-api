--TEST--
Function -- vprintf
--FILE--
<?php
require_once 'PHP/Compat/Function/vprintf.php';

$values = array (2, 'car');

$format = "There are %d monkeys in the %s";
php_compat_vprintf($format, $values);
?>
--EXPECT--
There are 2 monkeys in the car