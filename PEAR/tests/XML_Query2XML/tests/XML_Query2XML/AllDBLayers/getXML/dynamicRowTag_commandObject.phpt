--TEST--
XML_Query2XML::getXML(): dynamicRowTag: COMMAND OBJECT
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once 'XML/Query2XML/Callback.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';

class RowTagGenerator implements XML_Query2XML_Callback
{
    public function execute(array $record)
    {
        return $record['last_name'];
    }
}

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
            'dynamicRowTag' => new RowTagGenerator(),
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
    <Doe>jane.doe@example.com</Doe>
  </customer>
  <customer>
    <customerid>2</customerid>
    <Doe>john.doe@example.com</Doe>
  </customer>
  <customer>
    <customerid>3</customerid>
    <Green>susan.green@example.com</Green>
  </customer>
  <customer>
    <customerid>4</customerid>
    <Alt>victory.alt@example.com</Alt>
  </customer>
  <customer>
    <customerid>5</customerid>
    <Rippy>will.wippy@example.com</Rippy>
  </customer>
  <customer>
    <customerid>6</customerid>
    <Raw>tim.raw@example.com</Raw>
  </customer>
  <customer>
    <customerid>7</customerid>
    <Fallow>nick.fallow@example.com</Fallow>
  </customer>
  <customer>
    <customerid>8</customerid>
    <Burton>ed.burton@example.com</Burton>
  </customer>
  <customer>
    <customerid>9</customerid>
    <Woo>jack.woo@example.com</Woo>
  </customer>
  <customer>
    <customerid>10</customerid>
    <Gonzales>maria.gonzales@example.com</Gonzales>
  </customer>
</customers>
