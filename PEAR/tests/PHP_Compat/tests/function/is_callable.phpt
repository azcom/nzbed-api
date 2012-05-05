--TEST--
Function -- is_callable
--FILE--
<?php
require_once 'PHP/Compat/Function/is_callable.php';

class Foo { function Serialize() { return 'foo'; } }
$a = new Foo;

$testValues = array(
    1,
    3.14,
    false,
    array(),
    array('Foo', 'serialize', 'me'),
    array('Food', 'serialize'),
    array('Foo', 'serializez'),
    array($a, 'serializez'),
    array($a, 'serialize', 'me'),
    'substr',
    'Substr',
    array('Foo', 'serialize'),
    array('Foo', 'serialize'),
    array('foo', 'Serialize'),
    array($a, 'serialize'),
    array($a, 'Serialize')
);

foreach ($testValues as $test) {
    var_dump(php_compat_is_callable($test));
}

?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

