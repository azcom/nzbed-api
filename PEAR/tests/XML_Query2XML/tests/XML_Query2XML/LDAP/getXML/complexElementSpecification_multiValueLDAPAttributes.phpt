--TEST--
XML_Query2XML::getXML(): LDAP multi-value attributes with complex element specification
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
                'mail',
                'telephoneNumber',
                'labeledURI',
                'pager',
                'mobile'
            )
        )
    ),
    array(
        'rootTag' => 'persons',
        'rowTag' => 'person',
        'idColumn' => 'cn',
        'elements' => array(
            'cn' => array(
                'idColumn' => 'cn',
                'value' => 'cn'
            ),
            'sn' => array(
                'idColumn' => 'sn',
                'value' => 'sn'
            ),
            'mail' => array(
                'idColumn' => 'mail',
                'value' => 'mail'
            ),
            'telephoneNumber' => array(
                'idColumn' => 'telephoneNumber',
                'value' => 'telephoneNumber'
            ),
            'labeledURI' => array(
                'idColumn' => 'labeledURI',
                'value' => 'labeledURI'
            ),
            'pager' => array(
                'idColumn' => 'pager',
                'value' => 'pager'
            ),
            'mobile' => array(
                'idColumn' => 'mobile',
                'value' => 'mobile'
            )
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
    <mail>johndoe@example.com</mail>
    <mail>john.doe@example.com</mail>
    <telephoneNumber>555-111-222</telephoneNumber>
    <telephoneNumber>555-222-333</telephoneNumber>
    <labeledURI>http://john.example.com</labeledURI>
    <labeledURI>http://johndoe.example.com</labeledURI>
    <mobile>666-777-888</mobile>
  </person>
  <person>
    <cn>Jane Doe</cn>
    <sn>Doe</sn>
    <mail>jane@example.com</mail>
    <mail>jane.doe@example.com</mail>
    <telephoneNumber>555-200-300</telephoneNumber>
    <labeledURI>http://jane.example.com</labeledURI>
    <labeledURI>http://janedoe.example.com</labeledURI>
    <labeledURI>http://jane.doe.example.com</labeledURI>
    <pager>555-555-6789</pager>
    <mobile>555-777-888</mobile>
  </person>
  <person>
    <cn>Susi Weintraub</cn>
    <sn>Weintraub</sn>
    <mail>susi@example.com</mail>
    <telephoneNumber>555-111-222</telephoneNumber>
    <labeledURI>http://susi.example.com</labeledURI>
    <labeledURI>http://susiweintraub.example.com</labeledURI>
    <labeledURI>http://susi.weintraub.example.com</labeledURI>
    <mobile>555-666-777</mobile>
    <mobile>555-777-888</mobile>
  </person>
  <person>
    <cn>Jim Wells</cn>
    <sn>Wells</sn>
    <mail>jim@example.com</mail>
    <mail>jim.wells@example.com</mail>
    <mail>jimwells@example.com</mail>
    <mail>jwells@example.com</mail>
    <telephoneNumber>555-444-888</telephoneNumber>
    <labeledURI>http://jimwells.example.com</labeledURI>
    <labeledURI>http://jim.wells.example.com</labeledURI>
    <mobile>555-666-777</mobile>
  </person>
</persons>
