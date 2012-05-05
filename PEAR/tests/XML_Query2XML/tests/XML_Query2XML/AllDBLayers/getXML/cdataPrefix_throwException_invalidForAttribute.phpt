--TEST--
XML_Query2XML::getXML(): throw XML_Query2XML_XMLException when CDATA SECTION prefix is used for a simple attribute specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $dom =& $query2xml->getXML(
            "SELECT
                *
             FROM
                artist
             ORDER BY
                artistid",
            array(
                'rootTag' => 'music_library',
                'rowTag' => 'artist',
                'idColumn' => 'artistid',
                'attributes' => array(
                    'genre' => '=genre'
                )
            )
        );
        $dom->formatOutput = true;
        print $dom->saveXML();
    } catch (XML_Query2XML_XMLException $e) {
        echo get_class($e) . ': ' . substr($e->getMessage(), 0, 199);
    }
?>
--EXPECT--
XML_Query2XML_XMLException: [attributes][genre]: A value of the type object cannot be used for an attribute value.