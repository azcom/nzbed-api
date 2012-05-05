--TEST--
Function -- array_uintersect
--FILE--
<?php
require_once 'PHP/Compat/Function/array_uintersect.php';

$array1 = array('a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red');
$array2 = array('a' => 'GREEN', 'B' => 'brown', 'yellow', 'red');

print_r(php_compat_array_uintersect($array1, $array2, 'strcasecmp'));
?>
--EXPECT--
Array
(
    [a] => green
    [b] => brown
    [0] => red
)