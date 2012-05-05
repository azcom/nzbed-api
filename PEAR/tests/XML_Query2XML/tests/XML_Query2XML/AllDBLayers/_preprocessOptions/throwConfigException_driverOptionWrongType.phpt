--TEST--
XML_Query2XML::_processComplexElementSpecification(): check for XML_Query2XML_ConfigException - "driver" not a XML_Query2XML_Driver
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $x = $query2xml->getXML(
            "SELECT
                *
             FROM
                artist",
            array(
                'rootTag' => 'music_library',
                'rowTag' => 'artist',
                'idColumn' => 'artistid',
                'elements' => array(
                    'name',
                    'album' => array(
                        'sql' => array(
                            'data' => array('artistid'),
                            'query' => 'SELECT * FROM album WHERE artist_id = ?',
                            'driver' => 'bogus'
                        ),
                        'idColumn' => 'albumid',
                        'elements' => array(
                            'title'
                        )
                    )
                )
            )
        );
        $x->formatOutput = true;
        print $x->saveXML();
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
    
    echo "\n";
    
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $x = $query2xml->getXML(
            "SELECT
                *
             FROM
                artist",
            array(
                'rootTag' => 'music_library',
                'rowTag' => 'artist',
                'idColumn' => 'artistid',
                'elements' => array(
                    'name',
                    'album' => array(
                        'sql' => array(
                            'data' => array('artistid'),
                            'query' => 'SELECT * FROM album WHERE artist_id = ?',
                            'driver' => $query2xml
                        ),
                        'idColumn' => 'albumid',
                        'elements' => array(
                            'title'
                        )
                    )
                )
            )
        );
        $x->formatOutput = true;
        print $x->saveXML();
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: [elements][album][sql][driver]: instance of XML_Query2XML_Driver expected, string given.
XML_Query2XML_ConfigException: [elements][album][sql][driver]: instance of XML_Query2XML_Driver expected, object given.
