--TEST--
Function -- array_slice
--FILE--
<?php
require_once 'PHP/Compat/Function/array_slice.php';

$input = array("a", "b", "c", "d", "e");

var_dump(array_slice($input, 2, -1));
var_dump(array_slice($input, 2, -1, true));
var_dump(array_slice($input, 0));
var_dump(array_slice($input, -2));
var_dump(array_slice($input, -2, true));
var_dump(array_slice($input, -2, -1, true));
?>
--EXPECT--
array(2) {
  [0]=>
  string(1) "c"
  [1]=>
  string(1) "d"
}
array(2) {
  [2]=>
  string(1) "c"
  [3]=>
  string(1) "d"
}
array(5) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
  [3]=>
  string(1) "d"
  [4]=>
  string(1) "e"
}
array(2) {
  [0]=>
  string(1) "d"
  [1]=>
  string(1) "e"
}
array(1) {
  [0]=>
  string(1) "d"
}
array(1) {
  [3]=>
  string(1) "d"
}
