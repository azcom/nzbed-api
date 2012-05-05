--TEST--
XML_Query2XML_Driver_LDAP::preprocessQuery(): regular operation
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/ldap_init.php';

$ldapDriver = XML_Query2XML_Driver::factory($ldap);
$query1 = array(
    'data' => ':inetOrgPerson',
    'base' => 'ou=people,dc=example,dc=com',
    'filter' => '(objectclass=?)',
    'options' => array(
        'attributes' => array(
            'cn',
            'mail',
        )
    )
);
$query2 = array(
    'data' => ':inetOrgPerson',
    'base' => 'ou=people,dc=example,dc=com',
    'filter' => XML_Query2XML_TESTS_LDAP_Helper::LDAP_Filter_factory($ldap, 'objectclass', 'equals', 'inetOrgPerson'),
    'options' => array(
        'attributes' => array(
            'cn',
            'mail',
        )
    )
);

$query3 = array(
    'filter' => XML_Query2XML_TESTS_LDAP_Helper::LDAP_Filter_factory($ldap, 'objectclass', 'equals', 'inetOrgPerson'),
    'options' => array(
        'attributes' => array(
            'cn',
            'mail',
        )
    )
);

$query4 = array(
    'options' => array(
        'attributes' => array(
            'cn',
            'mail',
        )
    )
);

$query5 = array(
    'base' => 'ou=people,dc=example,dc=com'
);

$query6 = array(
    'base' => 'ou=people,dc=example,dc=com',
    'filter' => '(objectclass=inetOrgPerson)'
);

$query7 = array();

print 'query1: ' . $ldapDriver->preprocessQuery($query1, '[config]');
print 'query2: ' . $ldapDriver->preprocessQuery($query2, '[config]');
print 'query3: ' . $ldapDriver->preprocessQuery($query3, '[config]');
print 'query4: ' . $ldapDriver->preprocessQuery($query4, '[config]');
print 'query5: ' . $ldapDriver->preprocessQuery($query5, '[config]') . "\n";
print 'query6: ' . $ldapDriver->preprocessQuery($query6, '[config]') . "\n";
print 'query7: ' . $ldapDriver->preprocessQuery($query7, '[config]') . "\n";
?>
--EXPECT--
query1: basedn:ou=people,dc=example,dc=com; filter:(objectclass=?); options:Array
(
    [attributes] => Array
        (
            [0] => cn
            [1] => mail
        )

)
query2: basedn:ou=people,dc=example,dc=com; filter:(objectclass=inetOrgPerson); options:Array
(
    [attributes] => Array
        (
            [0] => cn
            [1] => mail
        )

)
query3: basedn:default; filter:(objectclass=inetOrgPerson); options:Array
(
    [attributes] => Array
        (
            [0] => cn
            [1] => mail
        )

)
query4: basedn:default; options:Array
(
    [attributes] => Array
        (
            [0] => cn
            [1] => mail
        )

)
query5: basedn:ou=people,dc=example,dc=com
query6: basedn:ou=people,dc=example,dc=com; filter:(objectclass=inetOrgPerson)
query7: basedn:default
