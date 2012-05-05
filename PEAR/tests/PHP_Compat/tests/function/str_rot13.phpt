--TEST--
Function -- str_rot13
--FILE--
<?php
require_once 'PHP/Compat/Function/str_rot13.php';

$str = "The quick brown fox jumped over the lazy dog.";
echo php_compat_str_rot13($str);
?>
--EXPECT--
Gur dhvpx oebja sbk whzcrq bire gur ynml qbt.