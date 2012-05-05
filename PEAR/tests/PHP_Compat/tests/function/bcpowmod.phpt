--TEST--
Function -- bcpowmod
--SKIPIF--
<?php if (!extension_loaded('bcmath')) { echo 'Skip, cannot test bcpowmod() without bcmath extension'; } ?>
--FILE--
<?php
require_once 'PHP/Compat/Function/bcpowmod.php';

$tests = array(
    array('1', '5', '2'),
    array('3', '2', '3'),
    array('32323487987324234234324', '42', '17'),
    array('11987987387233223423435', '42', '1276576289873')
);

foreach ($tests as $test) {
    list($x, $y, $mod) = $test;
    $a = php_compat_bcpowmod($x, $y, $mod);
    $b = bcmod(bcpow($x, $y), $mod);
    echo "php_compat_bcpowmod($x, $y, $mod): $a, bcmod(bcpow($x, $y), $mod): $b\n";
}

?>
--EXPECT--
php_compat_bcpowmod(1, 5, 2): 1, bcmod(bcpow(1, 5), 2): 1
php_compat_bcpowmod(3, 2, 3): 0, bcmod(bcpow(3, 2), 3): 0
php_compat_bcpowmod(32323487987324234234324, 42, 17): 4, bcmod(bcpow(32323487987324234234324, 42), 17): 4
php_compat_bcpowmod(11987987387233223423435, 42, 1276576289873): 666662814820, bcmod(bcpow(11987987387233223423435, 42), 1276576289873): 666662814820