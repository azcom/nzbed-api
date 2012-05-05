--TEST--
Function -- ob_get_flush
--FILE--
<?php
require_once 'PHP/Compat/Function/ob_get_flush.php';

ob_start();
echo 'foo';
$buffer = php_compat_ob_get_flush();
echo $buffer;
?>
--EXPECT--
foofoo