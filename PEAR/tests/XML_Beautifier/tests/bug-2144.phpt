--TEST--
XML Beautifier - Bug #2144: High-UTF entities in attributed decoded as ?
--FILE--
<?php
/*
 * The bug report complains of entities being changed to '?" marks,
 * which I see happening on PHP 4.4.9 but not on PHP 5.2.4.  
 *
 * Note that this test case fails on PHP5 because the XML tag 
 * is not being included in the output.  That problem is 
 * already reported in Bug #5450.  This test case 
 * should begin passing on PHP5 after #5450 is fixed.
 */

require_once 'XML/Beautifier.php';

$xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
  <bogustag
attribute="&#x418;&#x43D;&#x43D;&#x43E;&#x432;&#x430;&#x446;&#x438;&#x43E;&#x43D;&#x43D;&#x44B;&#x439;&#x434;&#x430;&#x439;&#x434;&#x436;&#x435;&#x441;&#x442;">
    <content />
  </bogustag>
EOF;

$bf = new XML_Beautifier();
echo $bf->formatString( $xml);

?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<bogustag attribute="Инновационный дайджест">
    <content />
</bogustag>
