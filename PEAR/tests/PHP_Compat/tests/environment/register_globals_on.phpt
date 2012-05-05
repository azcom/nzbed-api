--TEST--
Environment - register_globals on
--INI--
register_globals=Off
--POST--
foo=bar
--FILE--
<?php
require_once 'PHP/Compat/Environment/register_globals_on.php';
var_dump(isset($GLOBALS['foo']) ? $GLOBALS['foo'] : false);
?>
--EXPECT--
string(3) "bar"