--TEST--
Function -- cosh
--FILE--
<?php
require_once 'PHP/Compat/Function/cosh.php';

$tests = array(0, 0.1, 0.5, 1, M_PI);

foreach ($tests as $test) {
    printf("%.4f\n", php_compat_cosh($test));
}
?>
--EXPECT--
1.0000
1.0050
1.1276
1.5431
11.5920