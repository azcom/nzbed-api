--TEST--
Function -- get_include_path
--FILE--
<?php
require_once 'PHP/Compat/Function/get_include_path.php';

if (php_compat_get_include_path() == ini_get('include_path')) {
    echo 'true';
}
?>
--EXPECT--
true