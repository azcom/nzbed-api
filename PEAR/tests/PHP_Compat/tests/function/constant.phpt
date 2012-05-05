--TEST--
Function -- constant
--FILE--
<?php
require_once 'PHP/Compat/Function/constant.php';

$constant = 'BAR';
define($constant, 'foo');
echo php_compat_constant($constant);
?>
--EXPECT--
foo