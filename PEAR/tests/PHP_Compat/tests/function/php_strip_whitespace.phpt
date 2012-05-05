--TEST--
Function -- php_strip_whitespace
--SKIPIF--
<?php if (!extension_loaded('tokenizer')) { echo 'skip'; } ?>
--FILE--
<?php
require_once 'PHP/Compat/Function/php_strip_whitespace.php';

// Here is some sample PHP code to write to the file
$string = '<?php
// PHP comment here

/*
 * Another PHP comment
 */

echo        microtime();
// Newlines are considered whitespace, and are removed too:
microtime();
?>';

// Create a temp file
$tmpfname = tempnam('/tmp', 'phpcompat');
$fh = fopen($tmpfname, 'w');
fwrite($fh, $string);

// Test
echo php_compat_php_strip_whitespace($tmpfname);

// Close
fclose($fh);
?>
--EXPECT--
<?php
 echo microtime(); microtime(); ?>