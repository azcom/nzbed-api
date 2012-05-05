--TEST--
XML_Query2XML::getGlobalOption(): check for XML_Query2XML_ConfigException when getting a non existing option
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $query2xml->getGlobalOption('nosuchthing');
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . substr($e->getMessage(), 0, 35);
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: No such global option: nosuchthing