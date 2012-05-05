--TEST--
XML_Query2XML::factory(): check for XML_Query2XML_DBException
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    try {
        $query2xml =& XML_Query2XML::factory("some string");
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: Argument passed to the XML_Query2XML constructor is not an instance of DB_common, MDB2_Driver_Common, ADOConnection, PDO, Net_LDAP or Net_LDAP2.
