--TEST--
XML_Query2XML::getXML(): dynamicRowTag: COLUMN NAME
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
    'rowTag' => 'customer',
    'elements' => array(
        'customerid',
        'name_and_email' => array(
            'dynamicRowTag' => 'first_name',
            'value' => 'email'
        )
    )
  )
);

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<customers>
  <customer>
    <customerid>1</customerid>
    <Jane>jane.doe@example.com</Jane>
  </customer>
  <customer>
    <customerid>2</customerid>
    <John>john.doe@example.com</John>
  </customer>
  <customer>
    <customerid>3</customerid>
    <Susan>susan.green@example.com</Susan>
  </customer>
  <customer>
    <customerid>4</customerid>
    <Victoria>victory.alt@example.com</Victoria>
  </customer>
  <customer>
    <customerid>5</customerid>
    <Will>will.wippy@example.com</Will>
  </customer>
  <customer>
    <customerid>6</customerid>
    <Tim>tim.raw@example.com</Tim>
  </customer>
  <customer>
    <customerid>7</customerid>
    <Nick>nick.fallow@example.com</Nick>
  </customer>
  <customer>
    <customerid>8</customerid>
    <Ed>ed.burton@example.com</Ed>
  </customer>
  <customer>
    <customerid>9</customerid>
    <Jack>jack.woo@example.com</Jack>
  </customer>
  <customer>
    <customerid>10</customerid>
    <Maria>maria.gonzales@example.com</Maria>
  </customer>
</customers>
