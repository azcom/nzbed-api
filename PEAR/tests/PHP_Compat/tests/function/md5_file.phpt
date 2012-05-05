--TEST--
Function -- md5_file
--FILE--
<?php
require_once 'PHP/Compat/Function/md5_file.php';

echo php_compat_md5_file(__FILE__);
?>
--EXPECT--
86c9d7992c16f3648612ec648848fc56