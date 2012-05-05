--TEST--
Function -- ini_get_all
--FILE--
<?php
require_once 'PHP/Compat/Function/ini_get_all.php';

if (is_array(php_compat_ini_get_all())) {
    echo "true\n";
}

if (is_array(php_compat_ini_get_all('standard'))) {
    echo "true\n";
}
?>
--EXPECT--
true
true