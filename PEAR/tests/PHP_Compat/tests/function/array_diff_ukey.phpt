--TEST--
Function -- array_diff_ukey
--FILE--
<?php
require_once 'PHP/Compat/Function/array_diff_ukey.php';

function key_compare_func($key1, $key2)
{
    if ($key1 == $key2) {
        return 0;
    } elseif ($key1 > $key2) {
        return 1;
    } else {
        return -1;
    }
}

$array1 = array('blue'  => 1, 'red'  => 2, 'green'  => 3, 'purple' => 4);
$array2 = array('green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan'   => 8);

print_r(php_compat_array_diff_ukey($array1, $array2, 'key_compare_func'));

?>
--EXPECT--
Array
(
    [red] => 2
    [purple] => 4
)