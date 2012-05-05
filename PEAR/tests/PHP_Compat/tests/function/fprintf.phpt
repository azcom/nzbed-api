--TEST--
Function -- fprintf
--FILE--
<?php
require_once 'PHP/Compat/Function/fprintf.php';

$tmpfname = tempnam('/tmp', 'php');
$handle = fopen($tmpfname, 'w');
php_compat_fprintf($handle, 'The %s went to the %s for %d days', 'dog', 'park', 2);
fclose($handle);
$data = implode('', file($tmpfname));
unlink($tmpfname);

echo $data;
?>
--EXPECT--
The dog went to the park for 2 days