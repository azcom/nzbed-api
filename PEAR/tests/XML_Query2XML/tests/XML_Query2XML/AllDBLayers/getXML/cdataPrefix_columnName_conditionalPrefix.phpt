--TEST--
XML_Query2XML::getXML(): CDATA SECTION prefix with column names and conditional prefix
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            *,
            '' AS genre2
         FROM
            artist
         ORDER BY
            artistid",
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'genre' => '?=genre',
                'genre2' => array(
                    'value' => '?=genre'
                ),
                'genre3' => '?=genre2',
                'genre4' => array(
                    'value' => '?=genre2'
                )
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
    <genre><![CDATA[Soul]]></genre>
    <genre2><![CDATA[Soul]]></genre2>
  </artist>
  <artist>
    <genre><![CDATA[Soul]]></genre>
    <genre2><![CDATA[Soul]]></genre2>
  </artist>
  <artist>
    <genre><![CDATA[Country and Soul]]></genre>
    <genre2><![CDATA[Country and Soul]]></genre2>
  </artist>
</music_library>
