--TEST--
XML_Query2XML::getGlobalOption(): getting the hidden_container_prefix option
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $query2xml->setGlobalOption('hidden_container_prefix', 'SKIPME');
    print $query2xml->getGlobalOption('hidden_container_prefix');
?>
--EXPECT--
SKIPME