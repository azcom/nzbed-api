--TEST--
XML_Query2XML::getXML(): check for XML_Query2XML_DBException - $sql argument contains invalid query
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
                LEFT JOIN non_existing_table ON non_existing_table.artist_id = artist.artistid",
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
                        'elements' => array(
                            'albumid',
                            'title',
                            'published_year',
                            'comment'
                        )
                    )
                )
            )
        );
    } catch (XML_Query2XML_DBException $e) {
        echo get_class($e) . ': ' . str_replace(' prepare ', ' execute ', substr($e->getMessage(), 0, 210));
    }
?>
--EXPECT--
XML_Query2XML_DBException: [sql]: Could not execute the following SQL query: SELECT
                *
             FROM
                artist
                LEFT JOIN non_existing_table ON non_existing_table.artist_id = artist.artistid
