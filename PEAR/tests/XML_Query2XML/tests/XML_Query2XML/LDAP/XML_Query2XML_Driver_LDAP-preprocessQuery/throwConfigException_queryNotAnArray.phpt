--TEST--
XML_Query2XML_Driver_LDAP::preprocessQuery(): check for XML_Query2XML_ConfigException - $query: array expected, string given
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/ldap_init.php';
    
    $ldapDriver = XML_Query2XML_Driver::factory($ldap);
    try {
        $query = 'some_string';
        echo $ldapDriver->preprocessQuery($query, '[config]');
    } catch (XML_Query2XML_ConfigException $e) {
        print $e->getMessage();
    }
    
    echo "\n";
    
    try {
        $query = $ldapDriver;
        echo $ldapDriver->preprocessQuery($query, '[config]');
    } catch (XML_Query2XML_ConfigException $e) {
        print $e->getMessage();
    }
?>
--EXPECT--
[config]: array expected, string given.
[config]: array expected, object given.
