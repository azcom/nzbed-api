--TEST--
XML Beautifer - Bug #5450: Parser strip many tags
--FILE--
<?php
/*
 * This test acts differently on PHP4 and PHP5...
 *
 * On PHP 4.4.9, the tags are not stripped at all,
 * but the output formatting isn't correct.
 *
 * On PHP 5.2.4, the tags are stripped as reported
 * on this bug.
 */

require_once 'XML/Beautifier.php';

$string = <<<EOF
<?xml version="1.0" encoding="iso-8859-1"?><!DOCTYPE bookmark SYSTEM "bookmark.dtd"><bookmark><category><![CDATA[ this cdata will be stripped ]]></category></bookmark>
EOF;

$xml = new XML_Beautifier();
echo $xml->formatString($string);
?>
--EXPECT--
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<!DOCTYPE bookmark SYSTEM "bookmark.dtd">
<bookmark>
    <category>
        <![CDATA[ this cdata will be stripped ]]>
    </category>
</bookmark>

