--TEST--
XML_Query2XML::_executeEncoder(): basic functionality
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
            '" . utf8_encode('Père Noël') . "' AS name,
            '" . 'Père Noël' . "' AS name2,
            '" . utf8_encode('Père Noël') . "' AS name3,
            '" . 'Père Noël' . "' AS name4
         FROM
            artist",
        array(
            'rootTag' => 'xmas',
            'rowTag' => 'CEO',
            'idColumn' => 'artistid',
            'encoder' => false,
            'elements' => array(
                'name_nativeUTF8_noEncoding' => 'name',
                'name_nonUTF8_noEncoding' => 'name2',
                'name_nativeUTF8_2ndEncoding' => array(
                    'value' => 'name3',
                    'encoder' => null
                ),
                'name_nonUTF8_encoding' => array(
                    'value' => 'name4',
                    'encoder' => null
                )
            )
        )
    );
    $items = $dom->getElementsByTagName('name_nativeUTF8_noEncoding');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->nodeValue === utf8_encode('Père Noël') ? "Y" : "N";
    }
    
    $items = $dom->getElementsByTagName('name_nonUTF8_noEncoding');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->nodeValue === 'Père Noël' ? "Y" : "N";
    }
    
    $items = $dom->getElementsByTagName('name_nativeUTF8_2ndEncoding');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->nodeValue === utf8_encode(utf8_encode('Père Noël')) ? "Y" : "N";
    }
    
    $items = $dom->getElementsByTagName('name_nonUTF8_encoding');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->nodeValue === utf8_encode('Père Noël') ? "Y" : "N";
    }
    echo "\n";
    
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
YYYYYYYYYYYY
<?xml version="1.0" encoding="UTF-8"?>
<xmas>
  <CEO>
    <name_nativeUTF8_noEncoding>PÃ¨re NoÃ«l</name_nativeUTF8_noEncoding>
    <name_nonUTF8_noEncoding>Père Noël</name_nonUTF8_noEncoding>
    <name_nativeUTF8_2ndEncoding>PÃÂ¨re NoÃÂ«l</name_nativeUTF8_2ndEncoding>
    <name_nonUTF8_encoding>PÃ¨re NoÃ«l</name_nonUTF8_encoding>
  </CEO>
  <CEO>
    <name_nativeUTF8_noEncoding>PÃ¨re NoÃ«l</name_nativeUTF8_noEncoding>
    <name_nonUTF8_noEncoding>Père Noël</name_nonUTF8_noEncoding>
    <name_nativeUTF8_2ndEncoding>PÃÂ¨re NoÃÂ«l</name_nativeUTF8_2ndEncoding>
    <name_nonUTF8_encoding>PÃ¨re NoÃ«l</name_nonUTF8_encoding>
  </CEO>
  <CEO>
    <name_nativeUTF8_noEncoding>PÃ¨re NoÃ«l</name_nativeUTF8_noEncoding>
    <name_nonUTF8_noEncoding>Père Noël</name_nonUTF8_noEncoding>
    <name_nativeUTF8_2ndEncoding>PÃÂ¨re NoÃÂ«l</name_nativeUTF8_2ndEncoding>
    <name_nonUTF8_encoding>PÃ¨re NoÃ«l</name_nonUTF8_encoding>
  </CEO>
</xmas>