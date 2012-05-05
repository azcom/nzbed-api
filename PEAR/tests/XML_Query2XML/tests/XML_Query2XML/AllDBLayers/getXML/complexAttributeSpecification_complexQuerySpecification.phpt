--TEST--
XML_Query2XML::getXML(): complex attribute specification with complex query specification
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
                'name',
                'firstAlbumTitle' => array(
                    'value' => 'title',
                    'sql' => array(
                        'data' => array(
                            'artistid'
                        ),
                        'query' => "SELECT * FROM album WHERE artist_id = ?"
                    )
                ),
                'firstAlbumYear' => array(
                    'value' => 'published_year',
                    'sql' => array(
                        'data' => array(
                            'artistid'
                        ),
                        'query' => "SELECT * FROM album WHERE artist_id = ?"
                    )
                )
            )
        )
    );
    print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library><artist name="Curtis Mayfield" firstAlbumTitle="New World Order" firstAlbumYear="1990"/><artist name="Isaac Hayes" firstAlbumTitle="Shaft" firstAlbumYear="1972"/><artist name="Ray Charles"/></music_library>
