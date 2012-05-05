--TEST--
Function -- array_combine
--FILE--
<?php
require_once 'PHP/Compat/Function/array_combine.php';

$a = array('green', 'red', 'yellow');
$b = array('avocado', 'apple', 'banana');
$c = php_compat_array_combine($a, $b);

print_r($c);
?>
--EXPECT--
Array
(
    [green] => avocado
    [red] => apple
    [yellow] => banana
)