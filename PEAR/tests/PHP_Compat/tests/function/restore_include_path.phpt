--TEST--
Function -- restore_include_path
--FILE--
<?php
require_once 'PHP/Compat/Function/restore_include_path.php';

$orig = ini_get('include_path');
ini_set('include_path', 'foo');
echo ini_get('include_path'), "\n";

php_compat_restore_include_path();
$new = ini_get('include_path');

if ($orig == $new) {
    echo 'true';
}
?>
--EXPECT--
foo
true