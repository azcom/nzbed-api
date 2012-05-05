--TEST--
Function -- set_include_path
--FILE--
<?php
require_once 'PHP/Compat/Function/set_include_path.php';

php_compat_set_include_path('foo');
echo ini_get('include_path');
?>
--EXPECT--
foo