--TEST--
Function -- array_walk_recursive
--FILE--
<?php
require_once 'PHP/Compat/Function/array_walk_recursive.php';

$sweet = array('a' => 'apple', 'b' => 'banana');
$fruits = array('sweet' => $sweet, 'sour' => 'lemon');

function test_print($item, $key, $userdata)
{
   echo "$key holds $item $userdata\n";
}

php_compat_array_walk_recursive($fruits, 'test_print', 'hi');

function test_reference(&$item, $key)
{
    $item = 'hi';
}

php_compat_array_walk_recursive($fruits, 'test_reference');

echo $fruits['sweet']['a'], "\n"; // hi
?>
--EXPECT--
a holds apple hi
b holds banana hi
sour holds lemon hi
hi