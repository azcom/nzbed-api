--TEST--
XML_Query2XML_ISO9075Mapper::map(): ;
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML/ISO9075Mapper.php';
    try {
        XML_Query2XML_ISO9075Mapper::map("a\xff\xff");
    } catch (XML_Query2XML_ISO9075Mapper_Exception $e) {
        print $e->getMessage();
    }
?>
--EXPECT--
Malformed UTF-8 string
