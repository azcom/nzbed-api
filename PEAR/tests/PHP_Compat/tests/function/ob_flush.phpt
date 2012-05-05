--TEST--
Function -- ob_flush
--FILE--
<?php
require_once 'PHP/Compat/Function/ob_flush.php';

ob_start();
echo 'foo';
php_compat_ob_flush();
ob_end_clean();
?>
--EXPECT--
foo