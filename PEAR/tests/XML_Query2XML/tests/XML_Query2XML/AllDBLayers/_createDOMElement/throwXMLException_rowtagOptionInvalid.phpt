--TEST--
XML_Query2XML::_createDOMElement(): check for XML_Query2XML_XMLException - invalid XML name for rowTag
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
                'rowTag' => 'artist ',
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
    } catch (XML_Query2XML_XMLException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_XMLException: [rowTag/dynamicRowTag]: "artist " is an invalid XML element name: Invalid Character Error