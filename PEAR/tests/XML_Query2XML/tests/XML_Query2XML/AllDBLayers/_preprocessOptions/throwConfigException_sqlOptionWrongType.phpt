--TEST--
XML_Query2XML::_applySqlOptionsToRecord(): check for XML_Query2XML_ConfigException - "sql" not an array or string
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $query2xml->getXML(
            "SELECT
                *
             FROM
                artist
                LEFT JOIN album ON album.artist_id = artist.artistid",
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
                        'sql' => 1,
                        'rootTag' => 'albums',
                        'rowTag' => 'album',
                        'idColumn' => 'albumid',
                        'elements' => array(
                            'albumid' => 'albumid',
                            'title',
                            'published_year',
                            'comment'
                        )
                    )
                )
            )
        );
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: [elements][albums][sql]: array or string expected, integer given.
