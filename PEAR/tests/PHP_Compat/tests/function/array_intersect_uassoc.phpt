--TEST--
Function -- array_intersect_uassoc
--FILE--
<?php
require_once 'PHP/Compat/Function/array_intersect_uassoc.php';

$array1 = array("a" => "green", "b" => "brown", "c" => "blue", "red");
$array2 = array("a" => "GREEN", "B" => "brown", "yellow", "red");

print_r(php_compat_array_intersect_uassoc($array1, $array2, "strcasecmp"));

?>
--EXPECT--
Array
(
    [b] => brown
)