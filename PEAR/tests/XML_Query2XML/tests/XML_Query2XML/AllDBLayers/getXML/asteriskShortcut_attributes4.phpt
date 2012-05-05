--TEST--
XML_Query2XML::getXML(): asterisk shortcut with attributes - overwriting with complex attribute specification
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
                'genre' => array(
                    'value' => '#genre2upper();'
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
<music_library><artist artistid="1" name="Curtis Mayfield" birth_year="1920" birth_place="Chicago" genre="SOUL"/><artist artistid="2" name="Isaac Hayes" birth_year="1942" birth_place="Tennessee" genre="SOUL"/><artist artistid="3" name="Ray Charles" birth_year="1930" birth_place="Mississippi" genre="COUNTRY AND SOUL"/></music_library>
