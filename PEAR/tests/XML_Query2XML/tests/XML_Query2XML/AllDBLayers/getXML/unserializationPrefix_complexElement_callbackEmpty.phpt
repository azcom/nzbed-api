--TEST--
XML_Query2XML::getXML(): unserialization prefix with empty callback within complex element specification
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
                    'value' => '&#getEmptyString()'
                ),
                'building_xmldata2' => array(
                    'value' => '&#getNull()'
                ),
                'building_xmldata3' => array(
                    'value' => '?&#getEmptyString()'
                ),
                'building_xmldata4' => array(
                    'value' => '?&#getNull()'
                ),
                '__building_xmldata5' => array(
                    'value' => '&#getEmptyString()'
                ),
                '__building_xmldata6' => array(
                    'value' => '&#getNull()'
                )
                ,
                'building_xmldata7' => array(
                    'rowTag' => '__row',
                    'value' => '&#getEmptyString()'
                ),
                'building_xmldata8' => array(
                    'rowTag' => '__row',
                    'value' => '&#getNull()'
                )
            )
        )
    );
    $dom->formatOutput = true;
    print $dom->saveXML();
    
    function getEmptyString()
    {
        return '';
    }
    
    function getNull()
    {
        return null;
    }
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
    <building_xmldata/>
    <building_xmldata2/>
  </store>
  <store>
    <storeid>2</storeid>
    <country>US</country>
    <state>New York</state>
    <city>Larchmont</city>
    <street>Palmer Ave 71</street>
    <phone>456 7890</phone>
    <building_xmldata/>
    <building_xmldata2/>
  </store>
</music_stores>