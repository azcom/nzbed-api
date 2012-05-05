--TEST--
XML_Query2XML::_executeEncoder(): encoder for a simple attribute specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    function myEncoder($str)
    {
        return utf8_encode($str);
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
            'encoder' => 'myEncoder',
            'idColumn' => 'artistid',
            'attributes' => array(
                'name',
                'name2'
            )
        )
    );
    $items = $dom->getElementsByTagName('CEO');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->attributes->getNamedItem('name')->value === utf8_encode(utf8_encode('Père Noël')) ? "Y" : "N";
    }
    
    $items = $dom->getElementsByTagName('CEO');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->attributes->getNamedItem('name2')->value === utf8_encode('Père Noël') ? "Y" : "N";
    }
    
    echo "\n";
    
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
YYYYYY
<?xml version="1.0" encoding="UTF-8"?>
<xmas>
  <CEO name="PÃÂ¨re NoÃÂ«l" name2="PÃ¨re NoÃ«l"/>
  <CEO name="PÃÂ¨re NoÃÂ«l" name2="PÃ¨re NoÃ«l"/>
  <CEO name="PÃÂ¨re NoÃÂ«l" name2="PÃ¨re NoÃ«l"/>
</xmas>