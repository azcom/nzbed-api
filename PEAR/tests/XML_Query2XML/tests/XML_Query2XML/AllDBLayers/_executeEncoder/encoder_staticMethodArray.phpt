--TEST--
XML_Query2XML::_executeEncoder(): static encoder method (as array)
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    class MyClass
    {
        function myEncoder($str)
        {
            return utf8_encode($str);
        }
    }
    
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            artistid,
            '" . utf8_encode('Père Noël') . "' AS name,
            '" . 'Père Noël' . "' AS name2
         FROM
            artist",
        array(
            'rootTag' => 'xmas',
            'rowTag' => 'CEO',
            'encoder' => array('MyClass', 'myEncoder'),
            'idColumn' => 'artistid',
            'elements' => array(
                'name',
                'name2'
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