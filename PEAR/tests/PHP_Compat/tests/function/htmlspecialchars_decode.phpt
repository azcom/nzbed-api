--TEST--
Function -- htmlspecialchars_decode
--FILE--
<?php
require_once 'PHP/Compat/Function/htmlspecialchars_decode.php';

$text = 'Text &amp; &quot; &#039; &lt; &gt; End Text';
echo $text, "\n";
echo php_compat_htmlspecialchars_decode($text), "\n";
echo php_compat_htmlspecialchars_decode($text, ENT_COMPAT), "\n";
echo php_compat_htmlspecialchars_decode($text, ENT_QUOTES), "\n";
echo php_compat_htmlspecialchars_decode($text, ENT_NOQUOTES), "\n";

// bug #14138
echo php_compat_htmlspecialchars_decode('&amp;gt;') . "\n";

?>
--EXPECT--
Text &amp; &quot; &#039; &lt; &gt; End Text
Text & &quot; &#039; < > End Text
Text & " ' < > End Text
Text & " ' < > End Text
Text & &quot; &#039; < > End Text
&gt;