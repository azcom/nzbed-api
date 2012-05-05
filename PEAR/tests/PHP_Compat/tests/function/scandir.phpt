--TEST--
Function -- scandir
--FILE--
<?php
require_once 'PHP/Compat/Function/scandir.php';

// Create a folder and fill it with files
mkdir('tmp');
touch('tmp/test1');
touch('tmp/test2');

// Scan it
$dir    = 'tmp';
// Not sorted
$files = php_compat_scandir($dir);
// Sorted
$files2 = php_compat_scandir($dir, 1);

// List the results
print_r($files);
print_r($files2);

// Remove the files
unlink('tmp/test1');
unlink('tmp/test2');
rmdir('tmp');
?>
--EXPECT--
Array
(
    [0] => .
    [1] => ..
    [2] => test1
    [3] => test2
)
Array
(
    [0] => test2
    [1] => test1
    [2] => ..
    [3] => .
)