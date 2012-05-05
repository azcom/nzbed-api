--TEST--
XML_Query2XML::getFlatXML(): check for XML_Query2XML_DBException
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $query2xml->getFlatXML('SELECT * FROM non_existing_table');
    } catch (XML_Query2XML_DBException $e) {
        echo get_class($e) . ': ' . str_replace(' prepare ', ' execute ', substr($e->getMessage(), 0, 87));
    }
    
?>
--EXPECT--
XML_Query2XML_DBException: getFlatXML: Could not execute the following SQL query: SELECT * FROM non_existing_table