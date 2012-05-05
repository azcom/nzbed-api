--TEST--
XML_Query2XML::_mapSQLIdentifierToXMLName(): check for XML_Query2XML_ConfigException - mapper function throwing exception
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    class Helper
    {
        public function throwException($str)
        {
            throw new Exception('Throwing exception for ' . $str);
        }
    }
    
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
                'mapper' => array('Helper', 'throwException'),
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
                            '*'
                        )
                    )
                )
            )
        );
    } catch (XML_Query2XML_XMLException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_XMLException: [elements][artistid]: Could not map "artistid" to an XML name using the mapper Helper::throwException: Throwing exception for artistid
