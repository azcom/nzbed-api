--TEST--
Function -- file_put_contents
--FILE--
<?php
require_once 'PHP/Compat/Function/file_put_contents.php';

// Create a temp file
$tmpfname = tempnam('/tmp', 'phpcompat');

// With a string
$string = "abcd";

echo php_compat_file_put_contents($tmpfname, $string), "\n";
echo implode('', file($tmpfname)), "\n";

// With an array
$string = array('foo', 'bar');

echo php_compat_file_put_contents($tmpfname, $string), "\n";
echo implode('', file($tmpfname)), "\n";

// Test append
$string = 'foobar';
$string2 = 'testtest';
$tmpfname = tempnam('/tmp', 'php');

echo php_compat_file_put_contents($tmpfname, $string), "\n";
echo php_compat_file_put_contents($tmpfname, $string2, FILE_APPEND), "\n";
echo implode('', file($tmpfname)), "\n";
echo php_compat_file_put_contents($tmpfname, $string2), "\n";
echo implode('', file($tmpfname));

unlink($tmpfname);
?>
--EXPECT--
4
abcd
6
foobar
6
8
foobartesttest
8
testtest