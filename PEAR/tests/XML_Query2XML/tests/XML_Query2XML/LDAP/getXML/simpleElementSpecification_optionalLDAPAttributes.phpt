--TEST--
XML_Query2XML::getXML(): optional LDAP attributes with simple element specifications
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/ldap_init.php';

$query2xml = XML_Query2XML::factory($ldap);
$dom = $query2xml->getXML(
    array(
        'base' => 'ou=people,dc=example,dc=com',
        'filter' => '(objectclass=inetOrgPerson)',
        'options' => array(
            'attributes' => array(
                'cn',
                'sn',
                'pager'
            )
        )
    ),
    array(
        'rootTag' => 'persons',
        'rowTag' => 'person',
        'idColumn' => 'cn',
        'elements' => array(
            'cn',
            'sn',
            'pager',
            'optional_pager' => '?pager'
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
    <pager/>
  </person>
  <person>
    <cn>Jane Doe</cn>
    <sn>Doe</sn>
    <pager>555-555-6789</pager>
    <optional_pager>555-555-6789</optional_pager>
  </person>
  <person>
    <cn>Susi Weintraub</cn>
    <sn>Weintraub</sn>
    <pager/>
  </person>
  <person>
    <cn>Jim Wells</cn>
    <sn>Wells</sn>
    <pager/>
  </person>
</persons>
