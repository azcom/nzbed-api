--TEST--
XML_Query2XML_ISO9075Mapper::map(): " "
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML/ISO9075Mapper.php';
    print md5(XML_Query2XML_ISO9075Mapper::map("xml: @_x_Xtest#{}<>=;ThiS_iS_JuSt_sOMe_ReGUlAR_tExT\xe0\xae\xb4"));
?>
--EXPECT--
25b9e978ab33bb4e514cd3df0cc175e1
