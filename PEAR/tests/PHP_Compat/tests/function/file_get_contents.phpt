--TEST--
Function -- file_get_contents
--FILE--
<?php
require_once 'PHP/Compat/Function/file_get_contents.php';

$tmpfname = tempnam('/tmp', 'php');
$handle = fopen($tmpfname, 'w');
fwrite($handle, "test test");
fclose($handle);

echo php_compat_file_get_contents($tmpfname);

unlink($tmpfname);
?>
--EXPECT--
test test