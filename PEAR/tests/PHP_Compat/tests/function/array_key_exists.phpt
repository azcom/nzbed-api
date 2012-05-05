--TEST--
Function -- array_key_exists
--FILE--
<?php
require_once 'PHP/Compat/Function/array_key_exists.php';

$search_array = array("first" => 1, "second" => 4);
if (php_compat_array_key_exists("first", $search_array)) {
   echo "The 'first' element is in the array";
}
?>
--EXPECT--
The 'first' element is in the array