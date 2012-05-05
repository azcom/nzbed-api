--TEST--
XML_Query2XML_ISO9075Mapper::map(): test (unmodified)
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML/ISO9075Mapper.php';
    print XML_Query2XML_ISO9075Mapper::map("test");
?>
--EXPECT--
test
