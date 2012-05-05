--TEST--
Environment - register_long_arrays on
--INI--
register_long_arrays=Off
--FILE--
<?php
require_once 'PHP/Compat/Environment/register_long_arrays_on.php';
var_dump(isset($GLOBALS['HTTP_GET_VARS'], $GLOBALS['HTTP_POST_VARS'], $GLOBALS['HTTP_COOKIE_VARS'], $GLOBALS['HTTP_SERVER_VARS']));
?>
--EXPECT--
bool(true)