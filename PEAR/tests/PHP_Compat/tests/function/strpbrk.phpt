--TEST--
Function -- strpbrk
--FILE--
<?php
require_once 'PHP/Compat/Function/strpbrk.php';

$haystack = 'To be or not to be';
$char_list  = 'jhdn';

var_dump(php_compat_strpbrk($haystack, $char_list));
?>
--EXPECT--
string(9) "not to be"