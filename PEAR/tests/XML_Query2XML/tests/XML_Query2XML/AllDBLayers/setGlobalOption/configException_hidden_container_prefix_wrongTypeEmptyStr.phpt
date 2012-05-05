--TEST--
XML_Query2XML::setGlobalOption(): check for XML_Query2XML_ConfigException when setting the hidden_container_prefix option with a wrong type (string)
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $query2xml->setGlobalOption('hidden_container_prefix', '');
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . substr($e->getMessage(), 0, 78);
    }
    
class Test {}
?>
--EXPECT--
XML_Query2XML_ConfigException: The value for the hidden_container_prefix option has to be a non-empty string