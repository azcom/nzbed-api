--TEST--
XML_Query2XML::_executeEncoder(): overwriting default encoder with false (for 'value')
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            artistid,
            '" . utf8_encode('Père Noël') . "' AS name
         FROM
            artist",
        array(
            'rootTag' => 'xmas',
            'rowTag' => 'CEO',
            'idColumn' => 'artistid',
            'elements' => array(
                'name',
                'name2' => array(
                    'encoder' => false,
                    'value' => 'name'
                )
            )
        )
    );
    $items = $dom->getElementsByTagName('name');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->nodeValue === utf8_encode(utf8_encode('Père Noël')) ? "Y" : "N";
    }
    
    $items = $dom->getElementsByTagName('name2');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->nodeValue === utf8_encode('Père Noël') ? "Y" : "N";
    }
    
    echo "\n";
    
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
YYYYYY
<?xml version="1.0" encoding="UTF-8"?>
<xmas>
  <CEO>
    <name>PÃÂ¨re NoÃÂ«l</name>
    <name2>PÃ¨re NoÃ«l</name2>
  </CEO>
  <CEO>
    <name>PÃÂ¨re NoÃÂ«l</name>
    <name2>PÃ¨re NoÃ«l</name2>
  </CEO>
  <CEO>
    <name>PÃÂ¨re NoÃÂ«l</name>
    <name2>PÃ¨re NoÃ«l</name2>
  </CEO>
</xmas>