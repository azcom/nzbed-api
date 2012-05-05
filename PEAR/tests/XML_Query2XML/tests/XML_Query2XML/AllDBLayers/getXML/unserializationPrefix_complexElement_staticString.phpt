--TEST--
XML_Query2XML::getXML(): unserialization prefix with static string within complex element specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            *
         FROM
            store
         ORDER BY
            storeid",
        array(
            'rootTag' => 'music_stores',
            'rowTag' => 'store',
            'idColumn' => 'storeid',
            'elements' => array(
                'storeid',
                'country',
                'state',
                'city',
                'street',
                'phone',
                'building_xmldata' => array(
                    'value' => '&:<floors>1</floors>'
                ),
                'building_xmldata2' => array(
                    'rowTag' => '__container',
                    'value' => '&:<floors>2</floors>'
                ),
                '__building_xmldata3' => array(
                    'value' => '&:<floors>2</floors>'
                )
            )
        )
    );
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_stores>
  <store>
    <storeid>1</storeid>
    <country>US</country>
    <state>New York</state>
    <city>New York</city>
    <street>Broadway &amp; 72nd Str</street>
    <phone>123 456 7890</phone>
    <building_xmldata>
      <floors>1</floors>
    </building_xmldata>
    <floors>2</floors>
    <floors>2</floors>
  </store>
  <store>
    <storeid>2</storeid>
    <country>US</country>
    <state>New York</state>
    <city>Larchmont</city>
    <street>Palmer Ave 71</street>
    <phone>456 7890</phone>
    <building_xmldata>
      <floors>1</floors>
    </building_xmldata>
    <floors>2</floors>
    <floors>2</floors>
  </store>
</music_stores>