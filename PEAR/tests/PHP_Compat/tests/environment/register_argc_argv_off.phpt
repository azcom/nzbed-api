--TEST--
Environment - register_argc_argv off
--INI--
register_argc_argv=On
--FILE--
<?php
require_once 'PHP/Compat/Environment/register_argc_argv_off.php';
var_dump(isset($_SERVER['argc'], $_SERVER['argv']));
?>
--EXPECT--
bool(false)
