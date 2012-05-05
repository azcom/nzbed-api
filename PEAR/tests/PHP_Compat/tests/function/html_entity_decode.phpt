--TEST--
Function -- html_entity_decode
--FILE--
<?php
require_once 'PHP/Compat/Function/html_entity_decode.php';

$string = "I&#039;ll &quot;walk&quot; the &lt;b&gt;dog&lt;/b&gt; now";
echo php_compat_html_entity_decode($string), "\n";
echo php_compat_html_entity_decode($string, ENT_COMPAT), "\n";
echo php_compat_html_entity_decode($string, ENT_QUOTES), "\n";
echo php_compat_html_entity_decode($string, ENT_NOQUOTES), "\n";
?>
--EXPECT--
I&#039;ll "walk" the <b>dog</b> now
I&#039;ll "walk" the <b>dog</b> now
I'll "walk" the <b>dog</b> now
I&#039;ll &quot;walk&quot; the <b>dog</b> now