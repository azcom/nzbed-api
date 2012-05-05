--TEST--
XML_Query2XML::_executeEncoder(): overwriting default encoder twice
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
            '" . utf8_encode('Père Noël') . "' AS name
         FROM
            artist",
        array(
            'rootTag' => 'xmas',
            'rowTag' => 'CEO',
            'idColumn' => 'artistid',
            'encoder' => null,
            'elements' => array(
                'name',
                'sub' => array(
                    'encoder' => false,
                    'elements' => array(
                        'name2' => 'name',
                        'sub' => array(
                            'encoder' => 'MyClass::myEncoder',
                            'elements' => array(
                                'name3' => 'name'
                            )
                        )
                    )
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
    
    $items = $dom->getElementsByTagName('name3');
    for ($i = 0; $i < $items->length; $i++) {
        echo $items->item($i)->nodeValue === utf8_encode(utf8_encode('Père Noël')) ? "Y" : "N";
    }
    
    echo "\n";
    
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
YYYYYYYYY
<?xml version="1.0" encoding="UTF-8"?>
<xmas>
  <CEO>
    <name>PÃÂ¨re NoÃÂ«l</name>
    <sub>
      <name2>PÃ¨re NoÃ«l</name2>
      <sub>
        <name3>PÃÂ¨re NoÃÂ«l</name3>
      </sub>
    </sub>
  </CEO>
  <CEO>
    <name>PÃÂ¨re NoÃÂ«l</name>
    <sub>
      <name2>PÃ¨re NoÃ«l</name2>
      <sub>
        <name3>PÃÂ¨re NoÃÂ«l</name3>
      </sub>
    </sub>
  </CEO>
  <CEO>
    <name>PÃÂ¨re NoÃÂ«l</name>
    <sub>
      <name2>PÃ¨re NoÃ«l</name2>
      <sub>
        <name3>PÃÂ¨re NoÃÂ«l</name3>
      </sub>
    </sub>
  </CEO>
</xmas>