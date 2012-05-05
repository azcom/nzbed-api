--TEST--
Function -- time_sleep_until
--SKIPIF--
<?php if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { echo 'skip'; } ?>
--FILE--
<?php
require_once 'PHP/Compat/Function/time_sleep_until.php';

function ehandler($no, $str)
{
    echo '(Warning) ';
}
set_error_handler('ehandler');

$time = time();
time_sleep_until($time + 3);
echo '3:', time() - $time;

echo PHP_EOL;

$time = time();
php_compat_time_sleep_until($time - 1);
echo '-1:', time() - $time;

restore_error_handler();
?>
--EXPECT--
3:3
(Warning) -1:0