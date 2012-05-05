--TEST--
XML_Query2XML::getXML(): LDAP search with alternative placeholders
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/ldap_init.php';

$query2xml = XML_Query2XML::factory($ldap);
$dom = $query2xml->getXML(
    array(
        'data' => array(
            ':people',
            ':example',
            ':',
            ':inetOrgPerson'
        ),
        'base' => 'ou=!,dc=!,dc=com',
        'filter' => '(!objectclass=!)',
        'options' => array(
            'query2xml_placeholder' => '!'
        )
    ),
    array(
        'rootTag' => 'persons',
        'rowTag' => 'person',
        'idColumn' => 'cn',
        'elements' => array(
            'cn',
            'sn',
            'mail'
        )
    )
);

$dom->formatOutput = true;
print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<persons>
  <person>
    <cn>John Doe</cn>
    <sn>Doe</sn>
    <mail>john@example.com</mail>
  </person>
  <person>
    <cn>Jane Doe</cn>
    <sn>Doe</sn>
    <mail>jane@example.com</mail>
  </person>
  <person>
    <cn>Susi Weintraub</cn>
    <sn>Weintraub</sn>
    <mail>susi@example.com</mail>
  </person>
  <person>
    <cn>Jim Wells</cn>
    <sn>Wells</sn>
    <mail>jim@example.com</mail>
  </person>
</persons>
