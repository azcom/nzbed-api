--TEST--
XML_Query2XML::getXML(): asterisk shortcut with elements - as placeholder
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
                'TAG1_*' => ':STATIC_VALUE',
                'TAG2_*' => ':VALUE_*',
                'TAG3_*' => '#someManipulation(*)'
            )
        )
    );
    print $dom->saveXML();
    
    function someManipulation($record, $columnName)
    {
        return "--" . $record[$columnName] . "--";
    }
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library><artist><artistid>1</artistid><name>Curtis Mayfield</name><birth_year>1920</birth_year><birth_place>Chicago</birth_place><genre>Soul</genre><TAG1_artistid>STATIC_VALUE</TAG1_artistid><TAG1_name>STATIC_VALUE</TAG1_name><TAG1_birth_year>STATIC_VALUE</TAG1_birth_year><TAG1_birth_place>STATIC_VALUE</TAG1_birth_place><TAG1_genre>STATIC_VALUE</TAG1_genre><TAG2_artistid>VALUE_artistid</TAG2_artistid><TAG2_name>VALUE_name</TAG2_name><TAG2_birth_year>VALUE_birth_year</TAG2_birth_year><TAG2_birth_place>VALUE_birth_place</TAG2_birth_place><TAG2_genre>VALUE_genre</TAG2_genre><TAG3_artistid>--1--</TAG3_artistid><TAG3_name>--Curtis Mayfield--</TAG3_name><TAG3_birth_year>--1920--</TAG3_birth_year><TAG3_birth_place>--Chicago--</TAG3_birth_place><TAG3_genre>--Soul--</TAG3_genre></artist><artist><artistid>2</artistid><name>Isaac Hayes</name><birth_year>1942</birth_year><birth_place>Tennessee</birth_place><genre>Soul</genre><TAG1_artistid>STATIC_VALUE</TAG1_artistid><TAG1_name>STATIC_VALUE</TAG1_name><TAG1_birth_year>STATIC_VALUE</TAG1_birth_year><TAG1_birth_place>STATIC_VALUE</TAG1_birth_place><TAG1_genre>STATIC_VALUE</TAG1_genre><TAG2_artistid>VALUE_artistid</TAG2_artistid><TAG2_name>VALUE_name</TAG2_name><TAG2_birth_year>VALUE_birth_year</TAG2_birth_year><TAG2_birth_place>VALUE_birth_place</TAG2_birth_place><TAG2_genre>VALUE_genre</TAG2_genre><TAG3_artistid>--2--</TAG3_artistid><TAG3_name>--Isaac Hayes--</TAG3_name><TAG3_birth_year>--1942--</TAG3_birth_year><TAG3_birth_place>--Tennessee--</TAG3_birth_place><TAG3_genre>--Soul--</TAG3_genre></artist><artist><artistid>3</artistid><name>Ray Charles</name><birth_year>1930</birth_year><birth_place>Mississippi</birth_place><genre>Country and Soul</genre><TAG1_artistid>STATIC_VALUE</TAG1_artistid><TAG1_name>STATIC_VALUE</TAG1_name><TAG1_birth_year>STATIC_VALUE</TAG1_birth_year><TAG1_birth_place>STATIC_VALUE</TAG1_birth_place><TAG1_genre>STATIC_VALUE</TAG1_genre><TAG2_artistid>VALUE_artistid</TAG2_artistid><TAG2_name>VALUE_name</TAG2_name><TAG2_birth_year>VALUE_birth_year</TAG2_birth_year><TAG2_birth_place>VALUE_birth_place</TAG2_birth_place><TAG2_genre>VALUE_genre</TAG2_genre><TAG3_artistid>--3--</TAG3_artistid><TAG3_name>--Ray Charles--</TAG3_name><TAG3_birth_year>--1930--</TAG3_birth_year><TAG3_birth_place>--Mississippi--</TAG3_birth_place><TAG3_genre>--Country and Soul--</TAG3_genre></artist></music_library>
