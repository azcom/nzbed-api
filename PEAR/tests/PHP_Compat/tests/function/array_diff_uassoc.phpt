--TEST--
Function -- array_diff_uassoc
--FILE--
<?php
require_once 'PHP/Compat/Function/array_diff_uassoc.php';

function key_compare_func($a, $b)
{
    if ($a === $b) {
        return 0;
    }

    return ($a > $b) ? 1 : -1;
}

$array1 = array('a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red');
$array2 = array('a' => 'green', 'yellow', 'red');
$result = php_compat_array_diff_uassoc($array1, $array2, 'key_compare_func');
print_r($result);

?>
--EXPECT--
Array
(
    [b] => brown
    [c] => blue
    [0] => red
)