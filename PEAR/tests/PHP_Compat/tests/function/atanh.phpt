--TEST--
Function -- atanh
--FILE--
<?php
require_once 'PHP/Compat/Function/atanh.php';

$tests = array(0.9, 0.2, 0.7, 0.666, -0.1, 0);

foreach ($tests as $test) {
    printf("%.4f\n", php_compat_atanh($test));
}
?>
--EXPECT--
1.4722
0.2027
0.8673
0.8035
-0.1003
0.0000