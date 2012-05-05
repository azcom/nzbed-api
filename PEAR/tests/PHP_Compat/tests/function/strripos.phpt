--TEST--
Function -- strripos
--FILE--
<?php
require_once 'PHP/Compat/Function/strripos.php';

$haystack = 'Cat Dinner Dog Lion Mouse Sheep Wolf Cat Dog Donut';
$needle  = 'DOG';

// Simple
var_dump(php_compat_strripos($haystack, $needle));

// With offset
var_dump(php_compat_strripos($haystack, $needle, 3));
var_dump(php_compat_strripos($haystack, $needle, 30));
var_dump(php_compat_strripos($haystack, $needle, 50));
var_dump(php_compat_strripos($haystack, $needle, -1));
var_dump(php_compat_strripos($haystack, $needle, -10));
var_dump(php_compat_strripos($haystack, $needle, -30));
var_dump(php_compat_strripos($haystack, $needle, -50));

// Test for Bug xx
var_dump(php_compat_strripos($haystack, 'How about no'));

// Test for negative offset scanning bug
var_dump(php_compat_strripos('abcdef', 'bc', -5));
var_dump(php_compat_strripos('aaafrogaa', 'frog', -5));

// Bug #5049
var_dump(php_compat_strripos('testing', 'test'));

?>
--EXPECT--
int(41)
int(41)
int(41)
bool(false)
int(41)
int(11)
int(11)
bool(false)
bool(false)
int(1)
int(3)
int(0)