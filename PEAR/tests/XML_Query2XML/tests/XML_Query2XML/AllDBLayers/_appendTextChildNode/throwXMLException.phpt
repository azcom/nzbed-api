--TEST--
XML_Query2XML::_appendTextChildNode(): check for XML_Query2XML_XMLException when returning object from callback
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
                    'genre' => '#getNewTestInstance()'
                )
            )
        );
    } catch (XML_Query2XML_XMLException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
    
function getNewTestInstance()
{
    return new Test();
}
class Test {}
?>
--EXPECT--
XML_Query2XML_XMLException: [elements][genre]: A value of the type object cannot be used for a text node.