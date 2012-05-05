--TEST--
XML_Query2XML::getXML(): [sql][limit] set to 0
--SKIPIF--
<?php $db_layers = array('MDB2', 'DB'); require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
$dom = $query2xml->getXML(
    array(
        'query' => 'SELECT * FROM employee',
        'limit' => 3,
        'offset' => 2
    ),
    array(
        'rootTag' => 'employees',
        'rowTag' => 'employee',
        'idColumn' => 'employeeid',
        'elements' => array(
            'employeename'
        )
    )
);

$dom->formatOutput = true;
print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<employees>
  <employee>
    <employeename>Steve Hack</employeename>
  </employee>
  <employee>
    <employeename>Joan Kerr</employeename>
  </employee>
  <employee>
    <employeename>Marcus Roth</employeename>
  </employee>
</employees>
