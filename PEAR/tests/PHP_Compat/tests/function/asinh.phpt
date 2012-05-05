--TEST--
Function -- asinh
--FILE--
<?php
require_once 'PHP/Compat/Function/asinh.php';

$tests = array(1, 2, M_PI, 30, 90, 180);

foreach ($tests as $test) {
    printf("%.4f\n", php_compat_asinh($test));
}
?>
--EXPECT--
0.8814
1.4436
1.8623
4.0946
5.1930
5.8861