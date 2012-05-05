--TEST--
XML_Query2XML::getXML(): CDATA SECTION prefix with callbacks
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    
    function callback($record)
    {
        return $record['genre'];
    }
    
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            *
         FROM
            artist
         ORDER BY
            artistid",
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'genre' => '=#callback',
                'genre2' => array(
                    'value' => '=#callback'
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
