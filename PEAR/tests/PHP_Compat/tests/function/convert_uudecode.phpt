--TEST--
Function -- convert_uudecode
--FILE--
<?php
require_once 'PHP/Compat/Function/convert_uudecode.php';

$string = base64_decode('NTUmQUk8UiFJPFIhQSgnLUk7NyFMOTIhVDk3LVQKYAo=');
echo php_compat_convert_uudecode($string);
?>
--EXPECT--
This is a simple test