--TEST--
Function -- stripos
--FILE--
<?php
require_once 'PHP/Compat/Function/stripos.php';

$haystack = 'Cat Dinner Dog Lion Mouse Sheep Wolf Cat Dog';
$needle  = 'DOG';

// Simple
var_dump(php_compat_stripos($haystack, $needle));

// With offset
var_dump(php_compat_stripos($haystack, $needle, 4));
var_dump(php_compat_stripos($haystack, $needle, 10));
var_dump(php_compat_stripos($haystack, $needle, 15));
var_dump(php_compat_stripos($haystack, 'idontexist', 15));
?>
--EXPECT--
int(11)
int(11)
int(11)
int(41)
bool(false)