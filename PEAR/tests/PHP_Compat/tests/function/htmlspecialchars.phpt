--TEST--
Function -- htmlspecialchars
--FILE--
<?php
require_once 'PHP/Compat/Function/htmlspecialchars.php';

echo php_compat_htmlspecialchars('foobar'), "\n";
echo php_compat_htmlspecialchars('foo<bar>'), "\n";
echo php_compat_htmlspecialchars('foobar& <bar baz="foo">'), "\n";
echo php_compat_htmlspecialchars('foobar&amp; <bar baz="foo">'), "\n";
echo php_compat_htmlspecialchars('foobar <bar baz="foo" baz=\'foo\'>', ENT_NOQUOTES), "\n";
echo php_compat_htmlspecialchars('foobar <bar baz="foo" baz=\'foo\'>', ENT_QUOTES), "\n";
echo php_compat_htmlspecialchars('foobar <bar baz="foo" baz=\'foo\'>'), "\n";
echo php_compat_htmlspecialchars('foobar &amp; &#010; &#x8a; &#dodgy;<bar baz="foo">', ENT_QUOTES, 'ISO-8859-1', false), "\n";
echo php_compat_htmlspecialchars('foobar& <bar baz="foo">'), "\n";
echo php_compat_htmlspecialchars('foobar <bar baz="foo" baz=\'foo\'>', ENT_COMPAT, 'UTF-8'), "\n";
echo php_compat_htmlspecialchars('foobar &amp; &#010; &#x8a; &#dodgy;<bar baz="foo">', ENT_QUOTES, 'UTF-8', false), "\n";
echo php_compat_htmlspecialchars('foobar& <bar baz="foo">', ENT_COMPAT, 'UTF-8'), "\n";
?>
--EXPECT--
foobar
foo&lt;bar&gt;
foobar&amp; &lt;bar baz=&quot;foo&quot;&gt;
foobar&amp;amp; &lt;bar baz=&quot;foo&quot;&gt;
foobar &lt;bar baz="foo" baz='foo'&gt;
foobar &lt;bar baz=&quot;foo&quot; baz=&#039;foo&#039;&gt;
foobar &lt;bar baz=&quot;foo&quot; baz='foo'&gt;
foobar &amp; &#010; &#x8a; &amp;#dodgy;&lt;bar baz=&quot;foo&quot;&gt;
foobar&amp; &lt;bar baz=&quot;foo&quot;&gt;
foobar &lt;bar baz=&quot;foo&quot; baz='foo'&gt;
foobar &amp; &#010; &#x8a; &amp;#dodgy;&lt;bar baz=&quot;foo&quot;&gt;
foobar&amp; &lt;bar baz=&quot;foo&quot;&gt;