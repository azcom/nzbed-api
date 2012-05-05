--TEST--
XML_Query2XML::_applySqlOptionsToRecord(): check for XML_Query2XML_ConfigException - non existing column in "merge_selective" with marge_master=true
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
                artist",
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
                            'data' => array(
                                'artistid'
                            ),
                            'query' => 'SELECT * FROM album WHERE artist_id = ?'
                        ),
                        'sql_options' => array(
                            'merge_master' => true,
                            'merge_selective' => array('genre ')
                        ),
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
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: [elements][albums][sql_options][merge_selective][0]: The column "genre " was not found in the result set.
