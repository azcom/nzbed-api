--TEST--
XML_Query2XML::getXML(): dynamicRowTag: static string
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
            'dynamicRowTag' => ':email',
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
    <email>jane.doe@example.com</email>
  </customer>
  <customer>
    <customerid>2</customerid>
    <email>john.doe@example.com</email>
  </customer>
  <customer>
    <customerid>3</customerid>
    <email>susan.green@example.com</email>
  </customer>
  <customer>
    <customerid>4</customerid>
    <email>victory.alt@example.com</email>
  </customer>
  <customer>
    <customerid>5</customerid>
    <email>will.wippy@example.com</email>
  </customer>
  <customer>
    <customerid>6</customerid>
    <email>tim.raw@example.com</email>
  </customer>
  <customer>
    <customerid>7</customerid>
    <email>nick.fallow@example.com</email>
  </customer>
  <customer>
    <customerid>8</customerid>
    <email>ed.burton@example.com</email>
  </customer>
  <customer>
    <customerid>9</customerid>
    <email>jack.woo@example.com</email>
  </customer>
  <customer>
    <customerid>10</customerid>
    <email>maria.gonzales@example.com</email>
  </customer>
</customers>
