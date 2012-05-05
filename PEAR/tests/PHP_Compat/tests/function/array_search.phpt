--TEST--
Function -- array_search
--FILE--
<?php
require_once 'PHP/Compat/Function/array_search.php';

$array = array(0 => 'blue', 1 => 'red', 2 => 'green', 3 => 'red');

var_dump(php_compat_array_search('green', $array));
var_dump(php_compat_array_search('red', $array));
?>
--EXPECT--
int(2)
int(1)