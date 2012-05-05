--TEST--
Function -- ob_clean
--FILE--
<?php
require_once 'PHP/Compat/Function/ob_clean.php';

ob_start();
echo 'foo';
php_compat_ob_clean();
echo 'foo';
ob_end_flush();
?>
--EXPECT--
foo