--TEST--
XML_Query2XML::_getNestedXMLRecord(): check for XML_Query2XML_ConfigException - "idColumn" has wrong type (an array)
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $dom = $query2xml->getXML(
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
                        'rootTag' => 'albums',
                        'rowTag' => 'album',
                        'idColumn' => '#getId()',
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
        $dom->formatOutput = true;
        print $dom->saveXML();
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
    
    function getId($record)
    {
        return array(1, 2);
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: [elements][albums][idColumn]: Must evaluate to a value that is not an object or an array.
