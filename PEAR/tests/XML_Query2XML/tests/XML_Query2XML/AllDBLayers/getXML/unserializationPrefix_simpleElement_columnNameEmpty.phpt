--TEST--
XML_Query2XML::getXML(): unserialization prefix with empty column within simple element specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            *, NULL AS building_xmldata2, '' AS building_xmldata3
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
                'building_xmldata2' => '&building_xmldata2',
                'building_xmldata3' => '&building_xmldata3',
                'building_xmldata4' => '?&building_xmldata3',
                'building_xmldata5' => '?&building_xmldata2',
                '__building_xmldata6' => '&building_xmldata2',
                '__building_xmldata7' => '&building_xmldata3'
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
    <building_xmldata2/>
    <building_xmldata3/>
  </store>
  <store>
    <storeid>2</storeid>
    <country>US</country>
    <state>New York</state>
    <city>Larchmont</city>
    <street>Palmer Ave 71</street>
    <phone>456 7890</phone>
    <building_xmldata2/>
    <building_xmldata3/>
  </store>
</music_stores>