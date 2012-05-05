--TEST--
XML_Query2XML::getXML(): base64 prefix with callbacks
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    
    function callback()
    {
        return "Soul";
    }
    
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
                'genre' => '^#callback',
                'genre2' => array(
                    'value' => '^#callback'
                )
            ),
            'attributes' => array(
                'genre' => '^#callback',
                'genre2' => array(
                    'value' => '^#callback'
                )
            ),
        )
    );
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library>
  <artist genre="U291bA==" genre2="U291bA==">
    <genre>U291bA==</genre>
    <genre2>U291bA==</genre2>
  </artist>
  <artist genre="U291bA==" genre2="U291bA==">
    <genre>U291bA==</genre>
    <genre2>U291bA==</genre2>
  </artist>
  <artist genre="U291bA==" genre2="U291bA==">
    <genre>U291bA==</genre>
    <genre2>U291bA==</genre2>
  </artist>
</music_library>
