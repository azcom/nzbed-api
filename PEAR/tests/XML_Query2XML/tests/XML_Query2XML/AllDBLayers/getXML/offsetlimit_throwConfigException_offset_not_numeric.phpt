--TEST--
XML_Query2XML::getXML(): XML_Query2XML_ConfigException: [sql][offset]: integer expected, string given.
--SKIPIF--
<?php $db_layers = array('MDB2', 'DB'); require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
try {
    $dom = $query2xml->getXML(
        array(
            'query' => 'SELECT * FROM artist',
            'offset' => 'not a string'
        ),
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'artistid',
                'name'
            )
        )
    );
} catch (XML_Query2XML_ConfigException $e) {
    echo get_class($e) . ': ' . $e->getMessage();
}
?>
--EXPECT--
XML_Query2XML_ConfigException: [sql][offset]: integer expected, string given.