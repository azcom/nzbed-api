--TEST--
Function -- ob_get_clean
--FILE--
<?php
require_once 'PHP/Compat/Function/ob_get_clean.php';

ob_start();
echo 'foo';
$buffer = php_compat_ob_get_clean();
echo $buffer;
?>
--EXPECT--
foo