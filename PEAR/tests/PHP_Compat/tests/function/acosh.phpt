--TEST--
Function -- acosh
--FILE--
<?php
require_once 'PHP/Compat/Function/acosh.php';

$tests = array(1, 2, M_PI, 30, 90, 180);

foreach ($tests as $test) {
    printf("%.4f\n", php_compat_acosh($test));
}
?>
--EXPECT--
0.0000
1.3170
1.8115
4.0941
5.1929
5.8861