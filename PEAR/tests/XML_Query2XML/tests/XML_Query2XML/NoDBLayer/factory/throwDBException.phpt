--TEST--
XML_Query2XML::factory(): check for XML_Query2XML_DBException
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once 'PEAR.php';
    $db = new PEAR_Error('error message');
    try {
        $query2xml =& XML_Query2XML::factory($db);
    } catch (XML_Query2XML_DriverException $e) {
        echo get_class($e) . ': ' . substr($e->getMessage(), 0, 13);
    }
?>
--EXPECT--
XML_Query2XML_DriverException: Driver error:
