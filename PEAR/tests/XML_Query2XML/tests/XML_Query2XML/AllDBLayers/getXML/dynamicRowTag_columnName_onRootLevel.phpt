--TEST--
XML_Query2XML::getXML(): dynamicRowTag: COLUMN NAME on root level
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
$dom = $query2xml->getXML(
  "SELECT * FROM customer",
  array(
    'rootTag' => 'customers',
    'idColumn' => 'customerid',
    'dynamicRowTag' => 'first_name',
    'value' => 'email'
  )
);

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<customers>
  <Jane>jane.doe@example.com</Jane>
  <John>john.doe@example.com</John>
  <Susan>susan.green@example.com</Susan>
  <Victoria>victory.alt@example.com</Victoria>
  <Will>will.wippy@example.com</Will>
  <Tim>tim.raw@example.com</Tim>
  <Nick>nick.fallow@example.com</Nick>
  <Ed>ed.burton@example.com</Ed>
  <Jack>jack.woo@example.com</Jack>
  <Maria>maria.gonzales@example.com</Maria>
</customers>
