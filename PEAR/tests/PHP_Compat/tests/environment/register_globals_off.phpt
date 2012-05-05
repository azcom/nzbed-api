--TEST--
Environment - register_globals off
--INI--
register_globals=On
--POST--
foo=bar
--FILE--
<?php
require_once 'PHP/Compat/Environment/register_globals_off.php';
var_dump(isset($GLOBALS['foo']) ? $GLOBALS['foo'] : false);
?>
--EXPECT--
bool(false)
