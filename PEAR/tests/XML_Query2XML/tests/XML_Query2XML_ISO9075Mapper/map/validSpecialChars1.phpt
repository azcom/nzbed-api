--TEST--
XML_Query2XML_ISO9075Mapper::map(): ;
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML/ISO9075Mapper.php';
    print XML_Query2XML_ISO9075Mapper::map("a\xe0\xae\xb4") === "a\xe0\xae\xb4";
?>
--EXPECT--
1
