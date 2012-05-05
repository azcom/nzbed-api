--TEST--
Function -- array_uintersect_assoc
--FILE--
<?php
require_once 'PHP/Compat/Function/array_uintersect_assoc.php';

$array1 = array('a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red');
$array2 = array('a' => 'GREEN', 'B' => 'brown', 'yellow', 'red');

print_r(php_compat_array_uintersect_assoc($array1, $array2, 'strcasecmp'));
?>
--EXPECT--
Array
(
    [a] => green
)