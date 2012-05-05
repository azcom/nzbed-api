--TEST--
XML_Query2XML::getXML(): complex attribute specification with condition option
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
            '' AS empty_column
         FROM
            artist
         ORDER BY
            artistid",
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'attributes' => array(
                'attr1' => array(
                    'value' => ':only for atistid 1',
                    'condition' => '#artistIdIsOne'
                ),
                'attr2' => array(
                    'value' => ':only for atistid 2',
                    'condition' => '#Callbacks::artistIdIsTwo'
                ),
                'attr3' => array(
                    'value' => ':only for atistid 3',
                    'condition' => '#Callbacks::artistIdIsThree()'
                ),
                'attr4' => array(
                    'value' => ':this text will never appear as a static empty string == false',
                    'condition' => ':'
                ),
                'attr4' => array(
                    'value' => ':will never appear as the column empty_column will allways == false',
                    'condition' => 'empty_column'
                ),
                'name'
            )
        )
    );
    print $dom->saveXML();
    
    function artistIdIsOne($record)
    {
        return $record['artistid'] == 1;
    }
    
    class Callbacks
    {
        function artistIdIsTwo($record)
        {
            return $record['artistid'] == 2;
        }
        
        function artistIdIsThree($record)
        {
            return $record['artistid'] == 3;
        }
    }
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library><artist attr1="only for atistid 1" name="Curtis Mayfield"/><artist attr2="only for atistid 2" name="Isaac Hayes"/><artist attr3="only for atistid 3" name="Ray Charles"/></music_library>
