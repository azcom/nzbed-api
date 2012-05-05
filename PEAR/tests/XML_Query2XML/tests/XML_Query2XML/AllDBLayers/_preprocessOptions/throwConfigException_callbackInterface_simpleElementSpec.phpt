--TEST--
XML_Query2XML::_applyColumnStringToRecord(): check for XML_Query2XML_ConfigException - using the callback interface for a simple element specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once 'XML/Query2XML/Callback.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    
    class Test {}
    
    $query2xml = XML_Query2XML::factory($db);
    try {
        $dom =& $query2xml->getXML(
            "SELECT
                *
             FROM
                album",
            array(
                'rootTag' => 'music_store',
                'rowTag' => 'album',
                'idColumn' => 'albumid',
                'elements' => array(
                    'albumid',
                    'title' => new Test()
                )
            )
        );
    } catch (XML_Query2XML_ConfigException $e) {
        print $e->getMessage();
    }
?>
--EXPECT--
[elements][title]: array, string or instance of XML_Query2XML_Callback expected, object given.
