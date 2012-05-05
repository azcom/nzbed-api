--TEST--
Function -- substr_compare
--FILE--
<?php
require_once 'PHP/Compat/Function/substr_compare.php';

echo php_compat_substr_compare("abcde", "bc", 1, 2), "\n";
echo php_compat_substr_compare("abcde", "bcg", 1, 2), "\n";
echo php_compat_substr_compare("abcde", "BC", 1, 2, true), "\n"; 
echo php_compat_substr_compare("abcde", "bc", 1, 3), "\n";
echo php_compat_substr_compare("abcde", "cd", 1, 2);
?>
--EXPECT--
0
0
0
1
-1