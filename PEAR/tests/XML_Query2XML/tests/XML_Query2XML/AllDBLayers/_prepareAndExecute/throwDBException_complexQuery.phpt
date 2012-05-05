--TEST--
XML_Query2XML::_prepareAndExecute(): check for XML_Query2XML_DBException - Complex Query Specification
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
                        'sql' => array(
                            'query' => 'SELECT * FROM non_existing_table'
                        ),
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
    } catch (XML_Query2XML_DBException $e) {
        echo get_class($e);
    }
?>
--EXPECT--
XML_Query2XML_DBException
