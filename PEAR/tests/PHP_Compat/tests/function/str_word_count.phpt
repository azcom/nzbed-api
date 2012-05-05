--TEST--
Function -- str_word_count
--FILE--
<?php
require_once 'PHP/Compat/Function/str_word_count.php';

$str = "Hello friend, you're \r\nsdf\tlooking    3865\t9879 good to\"day, yes \"sir\" you am!";
var_dump(php_compat_str_word_count($str));
print_r(php_compat_str_word_count($str, 1));
print_r(php_compat_str_word_count($str, 2));

$str = 'hello I am repeated repeated';
print_r(php_compat_str_word_count($str, 2));
?>
--EXPECT--
int(12)
Array
(
    [0] => Hello
    [1] => friend
    [2] => you're
    [3] => sdf
    [4] => looking
    [5] => good
    [6] => to
    [7] => day
    [8] => yes
    [9] => sir
    [10] => you
    [11] => am
)
Array
(
    [0] => Hello
    [6] => friend
    [14] => you're
    [23] => sdf
    [27] => looking
    [48] => good
    [53] => to
    [56] => day
    [61] => yes
    [66] => sir
    [71] => you
    [75] => am
)
Array
(
    [0] => hello
    [6] => I
    [8] => am
    [11] => repeated
    [20] => repeated
)