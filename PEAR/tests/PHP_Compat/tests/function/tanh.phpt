--TEST--
Function -- tanh
--FILE--
<?php
require_once 'PHP/Compat/Function/tanh.php';

$tests = array(0, 0.1, 0.5, 1, M_PI, 30);

foreach ($tests as $test) {
    printf("%.4f\n", php_compat_tanh($test));
}
?>
--EXPECT--
0.0000
0.0997
0.4621
0.7616
0.9963
1.0000