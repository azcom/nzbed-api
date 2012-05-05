--TEST--
XML_Query2XML::getXML(): complex attribute specification with value prefixes
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
                'static' => array(
                    'value' => ':some static text',
                ),
                'hide' => array(
                    'value' => '?:'
                ),
                'six' => array(
                    'value' => '#multiply(2, 3)'
                ),
                'hide2' => array(
                    'value' => '?#getEmptyString'
                ),
                'artistid' => array(
                    'value' => 'artistid'
                ),
                'genre' => array(
                    'value' => '#str2upper(genre)'
                ),
                'birth_year' => array(
                    'value' => '?birth_year'
                )
            )
        )
    );
    print $dom->saveXML();
    
    function str2upper($record, $columnName)
    {
        return strtoupper($record[$columnName]);
    }
    
    function multiply($record, $a, $b)
    {
        return $a * $b;
    }
    
    function getEmptyString()
    {
        return '';
    }
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library><artist static="some static text" six="6" artistid="1" genre="SOUL" birth_year="1920"/><artist static="some static text" six="6" artistid="2" genre="SOUL" birth_year="1942"/><artist static="some static text" six="6" artistid="3" genre="COUNTRY AND SOUL" birth_year="1930"/></music_library>
