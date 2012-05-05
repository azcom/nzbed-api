--TEST--
Function -- mkdir
--FILE--
<?php
require_once 'PHP/Compat/Function/mkdir.php';

/**
 * Delete a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.3
 * @link        http://aidanlister.com/repos/v/function.rmdirr.php
 * @param       string   $dirname    Directory to delete
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }

    // Simple delete for a file
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }

    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Recurse
        rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
    }

    // Clean up
    $dir->close();
    return rmdir($dirname);
}

$base = realpath('.');

$tests = array(
    array('foo'),
    array('foo2', 'bar'),
    array('foo3', 'bar', 'baz')
);

echo "\nabsolute paths:\n";
foreach ($tests as $v) {
    array_unshift($v, $base);
    $dir = implode(DIRECTORY_SEPARATOR, $v);
    var_dump(php_compat_mkdir($dir, 0777, true), is_dir($dir));
}

// clean up
foreach ($tests as $v) {
    rmdirr($v[0]);
}

echo "\nrelative paths:\n";
foreach ($tests as $v) {
    $dir = implode(DIRECTORY_SEPARATOR, $v);
    var_dump(php_compat_mkdir($dir, 0777, true), is_dir($dir));
}

// clean up
foreach ($tests as $v) {
    rmdirr($v[0]);
}
?>
--EXPECT--
absolute paths:
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

relative paths:
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)