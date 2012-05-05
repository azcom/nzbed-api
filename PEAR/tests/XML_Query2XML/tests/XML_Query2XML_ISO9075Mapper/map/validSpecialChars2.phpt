--TEST--
XML_Query2XML_ISO9075Mapper::map(): ;
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML/ISO9075Mapper.php';
    print 'a' . I18N_UnicodeString::unicodeCharToUtf8(hexdec(0x200C)) ===
        XML_Query2XML_ISO9075Mapper::map('a' . I18N_UnicodeString::unicodeCharToUtf8(hexdec(0x200C)));
?>
--EXPECT--
1
