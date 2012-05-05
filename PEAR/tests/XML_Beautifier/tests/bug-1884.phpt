--TEST--
XML Beautifier - Bug #1884:  Parser is changing &#246; to 
--FILE--
<?php
require_once 'XML/Beautifier.php';

$string = '<?xml version="1.0" encoding="ISO-8859-1"?><objekttitel>sch&#246;n</objekttitel>';

$xml = new XML_Beautifier();
echo $xml->formatString($string);
?>
--EXPECT--
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<objekttitel>sch&#246;n</objekttitel>
