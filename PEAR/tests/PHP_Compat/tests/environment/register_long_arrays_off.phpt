--TEST--
Environment - register_long_arrays off
--INI--
register_long_arrays=On
--FILE--
<?php
require_once 'PHP/Compat/Environment/register_long_arrays_off.php';
var_dump(isset($GLOBALS['HTTP_GET_VARS']) || isset($GLOBALS['HTTP_POST_VARS']) || isset($GLOBALS['HTTP_COOKIE_VARS']) || isset($GLOBALS['HTTP_SERVER_VARS']));
?>
--EXPECT--
bool(false)