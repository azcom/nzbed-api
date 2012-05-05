--TEST--
Function -- array_intersect_assoc
--FILE--
<?php
require_once 'PHP/Compat/Function/array_intersect_assoc.php';

$array1 = array("a" => "green", "b" => "brown", "c" => "blue", "red");
$array2 = array("a" => "green", "yellow", "red");
$result = php_compat_array_intersect_assoc($array1, $array2);
print_r($result);

?>
--EXPECT--
Array
(
    [a] => green
)