--TEST--
XML_Query2XML::getXML(): [sql][limit] set to 0
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
$dom = $query2xml->getXML(
    array(
        'query' => 'SELECT * FROM artist',
        'limit' => 0
    ),
    array(
        'rootTag' => 'music_library',
        'rowTag' => 'artist',
        'idColumn' => 'artistid',
        'elements' => array(
            'artistid',
            'name'
        )
    )
);

$dom->formatOutput = true;
print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library>
  <artist>
    <artistid>1</artistid>
    <name>Curtis Mayfield</name>
  </artist>
  <artist>
    <artistid>2</artistid>
    <name>Isaac Hayes</name>
  </artist>
  <artist>
    <artistid>3</artistid>
    <name>Ray Charles</name>
  </artist>
</music_library>
