--TEST--
Function -- getopt
--FILE--
<?php
require_once 'PHP/Compat/Function/getopt.php';

// Test 1
$argc = 9;
$argv = array(
    'script.php', '-f',
    'value for f', '-v', '-a',
    '--required', 'value',
    '--optional=optional value',
    '--option'
);
$shortopts  = "";
$shortopts .= "f:";  // Required value
$shortopts .= "v::"; // Optional value
$shortopts .= "abc"; // These options do not accept values

$longopts  = array(
    "required:",     // Required value
    "optional::",    // Optional value
    "option",        // No value
    "opt",           // No value
);
$options = php_compat_getopt($shortopts, $longopts);
var_dump($options);

// Test 2
$argc = 3;
$argv = array('script.php', '-fvalue', '-h');
$options = php_compat_getopt('f:hp:');
var_dump($options);

// Test 3
$argc = 2;
$argv = array('script.php', '-aaac');
$options = php_compat_getopt('abc');
var_dump($options);
?>
--EXPECT--
array(6) {
  ["f"]=>
  string(11) "value for f"
  ["v"]=>
  bool(false)
  ["a"]=>
  bool(false)
  ["required"]=>
  string(5) "value"
  ["optional"]=>
  string(14) "optional value"
  ["option"]=>
  bool(false)
}
array(2) {
  ["f"]=>
  string(5) "value"
  ["h"]=>
  bool(false)
}
array(2) {
  ["a"]=>
  array(3) {
    [0]=>
    bool(false)
    [1]=>
    bool(false)
    [2]=>
    bool(false)
  }
  ["c"]=>
  bool(false)
}