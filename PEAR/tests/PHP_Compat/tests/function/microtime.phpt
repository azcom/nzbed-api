--TEST--
Function -- microtime
--FILE--
<?php
require_once 'PHP/Compat/Function/microtime.php';

$time = time();
$microtime = php_compat_microtime();

list($usec, $sec) = explode(' ', $microtime);
echo "microtime():\ndifference to time() < 1: ", (int)(abs(((float)$usec + (float)$sec) - $time) < 1), "\n";
echo 'pattern matches: ', preg_match('/^0.\d{8} \d+\z/', $microtime), "\n\n";


$time = time();
$microtime = php_compat_microtime(true);
echo "microtime(true):\ndifference to time() < 1: ", (int)(abs($microtime - $time) < 1), "\n";
echo 'is_float: ', (int)is_float($microtime);

?>
--EXPECT--
microtime():
difference to time() < 1: 1
pattern matches: 1

microtime(true):
difference to time() < 1: 1
is_float: 1