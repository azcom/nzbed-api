--TEST--
XML_Query2XML::getXML(): asterisk shortcut with elements - overwriting with complex element specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
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
                '*' => '*',
                'genre' => array(
                    'value' => '#genre2upper()'
                )
            )
        )
    );
    print $dom->saveXML();
    
    function genre2upper($record)
    {
        return strtoupper($record['genre']);
    }
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library><artist><artistid>1</artistid><name>Curtis Mayfield</name><birth_year>1920</birth_year><birth_place>Chicago</birth_place><genre>SOUL</genre></artist><artist><artistid>2</artistid><name>Isaac Hayes</name><birth_year>1942</birth_year><birth_place>Tennessee</birth_place><genre>SOUL</genre></artist><artist><artistid>3</artistid><name>Ray Charles</name><birth_year>1930</birth_year><birth_place>Mississippi</birth_place><genre>COUNTRY AND SOUL</genre></artist></music_library>
