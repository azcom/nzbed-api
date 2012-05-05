--TEST--
Function -- array_fill
--FILE--
<?php
require_once 'PHP/Compat/Function/array_fill.php';

$a = array_fill(5, 6, 'banana');

foreach ($a as $k => $v) {
    echo "$k: $v\n";
}
?>
--EXPECT--
5: banana
6: banana
7: banana
8: banana
9: banana
10: banana