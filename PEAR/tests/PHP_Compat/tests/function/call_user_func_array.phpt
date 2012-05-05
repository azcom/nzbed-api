--TEST--
Function -- call_user_func_array
--FILE--
<?php
require_once 'PHP/Compat/Function/call_user_func_array.php';

function somefunc ($param1, $param2, $param3) {
	echo $param1, "\n", $param2, "\n", $param3;
}

$args = array ('foo', 'bar', 'meta');
php_compat_call_user_func_array('somefunc', $args);
?>
--EXPECT--
foo
bar
meta