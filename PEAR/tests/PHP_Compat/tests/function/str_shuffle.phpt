--TEST--
Function -- str_shuffle
--FILE--
<?php
require_once 'PHP/Compat/Function/str_shuffle.php';

$string = php_compat_str_shuffle('ab');
if ($string == 'ab' ||
    $string == 'ba' ||
    $string == 'aa' ||
    $string == 'bb') {

    echo "true";
}
?>
--EXPECT--
true