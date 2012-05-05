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
        'query' => 'SELECT * FROM employee WHERE employeeid > 4',
        'limit' => 3,
        'offset' => 2
    ),
    array(
        'rootTag' => 'employees',
        'rowTag' => 'employee',
        'idColumn' => 'employeeid',
        'elements' => array(
            'employeename',
            'sale' => array(
                'idColumn' => 'saleid',
                'sql' => array(
                    'data' => array('employeeid'),
                    'query' => 'SELECT * FROM sale s, album a WHERE s.album_id = a.albumid AND s.employee_id = ?',
                    'limit' => 2,
                    'offset' => 1
                ),
                'value' => 'title'
            )
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
    <employeename>Rita Doktor</employeename>
    <sale>New World Order</sale>
    <sale>Curtis</sale>
  </employee>
  <employee>
    <employeename>David Til</employeename>
    <sale>New World Order</sale>
    <sale>Curtis</sale>
  </employee>
  <employee>
    <employeename>Pia Eist</employeename>
    <sale>Curtis</sale>
  </employee>
</employees>
