--TEST--
Function -- property_exists
--FILE--
<?php
require_once 'PHP/Compat/Function/property_exists.php';

class Foo {
    var $bar = 'baz';
}
$foo = new Foo;
class Bar extends Foo {}
$bar = new Bar;

var_dump(property_exists($foo, 'bar'));
var_dump(property_exists($bar, 'bar'));
var_dump(property_exists($foo, 'baz'));

?>
--EXPECT--
bool(true)
bool(true)
bool(false)
