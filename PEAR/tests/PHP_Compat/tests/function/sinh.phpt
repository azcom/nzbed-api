--TEST--
Function -- sinh
--FILE--
<?php
require_once 'PHP/Compat/Function/sinh.php';

$tests = array(0, 0.1, 0.5, 1, M_PI);

foreach ($tests as $test) {
    printf("%.4f\n", php_compat_sinh($test));
}
?>
--EXPECT--
0.0000
0.1002
0.5211
1.1752
11.5487