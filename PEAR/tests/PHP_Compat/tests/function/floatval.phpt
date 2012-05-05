--TEST--
Function -- floatval
--FILE--
<?php
require_once 'PHP/Compat/Function/floatval.php';

$var = '12312.123';
var_dump(php_compat_floatval($var));
?>
--EXPECT--
float(12312.123)