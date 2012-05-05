--TEST--
XML_Query2XML::getXML(): idColumn & $sql: FALSE
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
$dom = $query2xml->getXML(
    false,
    array(
        'idColumn' => false,
        'rowTag' => '__tables',
        'rootTag' => 'music_store',
        'elements' => array(
            'artists' => array(
                'rootTag' => 'artists',
                'rowTag' => 'artist',
                'idColumn' => 'artistid',
                'sql' => 'SELECT * FROM artist',
                'elements' => array(
                    '*'
                )
            ),
            'albums' => array(
                'rootTag' => 'albums',
                'rowTag' => 'album',
                'idColumn' => 'albumid',
                'sql' => 'SELECT * FROM album',
                'elements' => array(
                    '*'
                )
            )
        )
    )
);

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_store>
  <artists>
    <artist>
      <artistid>1</artistid>
      <name>Curtis Mayfield</name>
      <birth_year>1920</birth_year>
      <birth_place>Chicago</birth_place>
      <genre>Soul</genre>
    </artist>
    <artist>
      <artistid>2</artistid>
      <name>Isaac Hayes</name>
      <birth_year>1942</birth_year>
      <birth_place>Tennessee</birth_place>
      <genre>Soul</genre>
    </artist>
    <artist>
      <artistid>3</artistid>
      <name>Ray Charles</name>
      <birth_year>1930</birth_year>
      <birth_place>Mississippi</birth_place>
      <genre>Country and Soul</genre>
    </artist>
  </artists>
  <albums>
    <album>
      <albumid>1</albumid>
      <artist_id>1</artist_id>
      <title>New World Order</title>
      <published_year>1990</published_year>
      <comment>the best ever!</comment>
    </album>
    <album>
      <albumid>2</albumid>
      <artist_id>1</artist_id>
      <title>Curtis</title>
      <published_year>1970</published_year>
      <comment>that man's got somthin' to say</comment>
    </album>
    <album>
      <albumid>3</albumid>
      <artist_id>2</artist_id>
      <title>Shaft</title>
      <published_year>1972</published_year>
      <comment>he's the man</comment>
    </album>
  </albums>
</music_store>
