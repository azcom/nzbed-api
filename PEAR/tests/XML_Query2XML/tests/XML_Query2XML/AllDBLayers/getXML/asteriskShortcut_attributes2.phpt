--TEST--
XML_Query2XML::getXML(): asterisk shortcut with attributes - as placeholder
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
            'attributes' => array(
                '*' => '*',
                'ATTR1_*' => ':STATIC_VALUE',
                'ATTR2_*' => ':VALUE_*',
                'ATTR3_*' => '#someManipulation(*)'
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
<music_library><artist artistid="1" name="Curtis Mayfield" birth_year="1920" birth_place="Chicago" genre="Soul" ATTR1_artistid="STATIC_VALUE" ATTR1_name="STATIC_VALUE" ATTR1_birth_year="STATIC_VALUE" ATTR1_birth_place="STATIC_VALUE" ATTR1_genre="STATIC_VALUE" ATTR2_artistid="VALUE_artistid" ATTR2_name="VALUE_name" ATTR2_birth_year="VALUE_birth_year" ATTR2_birth_place="VALUE_birth_place" ATTR2_genre="VALUE_genre" ATTR3_artistid="--1--" ATTR3_name="--Curtis Mayfield--" ATTR3_birth_year="--1920--" ATTR3_birth_place="--Chicago--" ATTR3_genre="--Soul--"/><artist artistid="2" name="Isaac Hayes" birth_year="1942" birth_place="Tennessee" genre="Soul" ATTR1_artistid="STATIC_VALUE" ATTR1_name="STATIC_VALUE" ATTR1_birth_year="STATIC_VALUE" ATTR1_birth_place="STATIC_VALUE" ATTR1_genre="STATIC_VALUE" ATTR2_artistid="VALUE_artistid" ATTR2_name="VALUE_name" ATTR2_birth_year="VALUE_birth_year" ATTR2_birth_place="VALUE_birth_place" ATTR2_genre="VALUE_genre" ATTR3_artistid="--2--" ATTR3_name="--Isaac Hayes--" ATTR3_birth_year="--1942--" ATTR3_birth_place="--Tennessee--" ATTR3_genre="--Soul--"/><artist artistid="3" name="Ray Charles" birth_year="1930" birth_place="Mississippi" genre="Country and Soul" ATTR1_artistid="STATIC_VALUE" ATTR1_name="STATIC_VALUE" ATTR1_birth_year="STATIC_VALUE" ATTR1_birth_place="STATIC_VALUE" ATTR1_genre="STATIC_VALUE" ATTR2_artistid="VALUE_artistid" ATTR2_name="VALUE_name" ATTR2_birth_year="VALUE_birth_year" ATTR2_birth_place="VALUE_birth_place" ATTR2_genre="VALUE_genre" ATTR3_artistid="--3--" ATTR3_name="--Ray Charles--" ATTR3_birth_year="--1930--" ATTR3_birth_place="--Mississippi--" ATTR3_genre="--Country and Soul--"/></music_library>
