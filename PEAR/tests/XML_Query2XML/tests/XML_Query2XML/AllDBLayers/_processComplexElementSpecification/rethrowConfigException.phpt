--TEST--
XML_Query2XML::_processComplexElementSpecification(): check for XML_Query2XML_ConfigException - bubbling up
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
                    'albums' => array(
                        'elements' => array(
                            'sub1' => array(
                                'elements' => array(
                                    'sub2' => array(
                                        'elements' => array(
                                            'sub3' => array(
                                                'sql' => array(
                                                    'data' => 1,
                                                    'query' => 'SELECT * FROM album WHERE artist_id = ?'
                                                ),
                                            )
                                        )
                                    )
                                )
                            )
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
XML_Query2XML_ConfigException: [elements][albums][elements][sub1][elements][sub2][elements][sub3][sql][data]: array expected, integer given.
