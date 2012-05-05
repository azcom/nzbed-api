--TEST--
XML_Query2XML::getXML(): value - condition prefix with column name
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            artist.*,
            album.*,
            '' AS test
         FROM
            artist
            LEFT JOIN album ON album.artist_id = artist.artistid
         ORDER BY
            artistid,
            albumid",
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'artistid',
                'name',
                'birth_year',
                'birth_place',
                'genre',
                'albums' => array(
                    'rootTag' => 'albums',
                    'rowTag' => 'album',
                    'idColumn' => 'albumid',
                    'value' => '?test',
                    'elements' => array(
                        'albumid',
                        'title',
                        'published_year',
                        'comment',
                    )
                )
            )
        )
    );
    print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library><artist><artistid>1</artistid><name>Curtis Mayfield</name><birth_year>1920</birth_year><birth_place>Chicago</birth_place><genre>Soul</genre><albums/></artist><artist><artistid>2</artistid><name>Isaac Hayes</name><birth_year>1942</birth_year><birth_place>Tennessee</birth_place><genre>Soul</genre><albums/></artist><artist><artistid>3</artistid><name>Ray Charles</name><birth_year>1930</birth_year><birth_place>Mississippi</birth_place><genre>Country and Soul</genre><albums/></artist></music_library>
