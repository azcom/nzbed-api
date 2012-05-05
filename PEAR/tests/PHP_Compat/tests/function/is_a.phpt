--TEST--
Function -- is_a
--FILE--
<?php
require_once 'PHP/Compat/Function/is_a.php';

class WidgetFactory
{
    var $oink = 'moo';
}

$wf = new WidgetFactory();

if (php_compat_is_a($wf, 'WidgetFactory')) {
    echo 'true';
}
?>
--EXPECT--
true