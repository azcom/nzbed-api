--TEST--
XML_Query2XML::_applyColumnStringToRecord(): check for XML_Query2XML_XMLException - unserializable data
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
                    'xml' => '&:<no_closing_tag>'
                )
            )
        );
    } catch (XML_Query2XML_XMLException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_XMLException: [elements][xml]: XML_Query2XML_Data_Processor_Unserialize: Could not unserialize the following XML data: "<no_closing_tag>"