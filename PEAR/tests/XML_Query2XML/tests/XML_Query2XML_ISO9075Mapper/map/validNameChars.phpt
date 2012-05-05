--TEST--
XML_Query2XML_ISO9075Mapper::map(): ;
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML/ISO9075Mapper.php';
    $validRanges[] = array(hexdec('C0'),    hexdec('D6'));
    $validRanges[] = array(hexdec('D8'),    hexdec('F6'));
    $validRanges[] = array(hexdec('F8'),    hexdec('2FF'));
    $validRanges[] = array(hexdec('300'),   hexdec('36F'));     //this is only for nameChar
    $validRanges[] = array(hexdec('370'),   hexdec('37D'));
    $validRanges[] = array(hexdec('37F'),   hexdec('1FFF'));
    $validRanges[] = array(hexdec('200C'),  hexdec('200D'));
    $validRanges[] = array(hexdec('203F'),  hexdec('2040'));    //this is only for nameChar
    $validRanges[] = array(hexdec('2070'),  hexdec('218F'));
    $validRanges[] = array(hexdec('2C00'),  hexdec('2FEF'));
    $validRanges[] = array(hexdec('3001'),  hexdec('D7FF'));
    $validRanges[] = array(hexdec('F900'),  hexdec('FDCF'));
    $validRanges[] = array(hexdec('FDF0'),  hexdec('FFFD'));
    $validRanges[] = array(hexdec('10000'), hexdec('EFFFF'));
    
    for ($i = 0; $i < count($validRanges); $i++) {
        //we only test min, max and avg or this would take ages
        $min = $validRanges[$i][0];
        $max = $validRanges[$i][1];
        $avg = ($min + $max) / 2;
        
        print 'min=' . $min . ': ';
        print 'a' . I18N_UnicodeString::unicodeCharToUtf8($min) ===
            XML_Query2XML_ISO9075Mapper::map('a' . I18N_UnicodeString::unicodeCharToUtf8($min));
        print "\n";
        
        print 'max=' . $max . ': ';
        print 'a' . I18N_UnicodeString::unicodeCharToUtf8($max) ===
            XML_Query2XML_ISO9075Mapper::map('a' . I18N_UnicodeString::unicodeCharToUtf8($max));
        print "\n";    
        
        print 'avg=' . $avg . ': ';
        print 'a' . I18N_UnicodeString::unicodeCharToUtf8($avg) ===
            XML_Query2XML_ISO9075Mapper::map('a' . I18N_UnicodeString::unicodeCharToUtf8($avg));
        print "\n\n";
    }
?>
--EXPECT--
min=192: 1
max=214: 1
avg=203: 1

min=216: 1
max=246: 1
avg=231: 1

min=248: 1
max=767: 1
avg=507.5: 1

min=768: 1
max=879: 1
avg=823.5: 1

min=880: 1
max=893: 1
avg=886.5: 1

min=895: 1
max=8191: 1
avg=4543: 1

min=8204: 1
max=8205: 1
avg=8204.5: 1

min=8255: 1
max=8256: 1
avg=8255.5: 1

min=8304: 1
max=8591: 1
avg=8447.5: 1

min=11264: 1
max=12271: 1
avg=11767.5: 1

min=12289: 1
max=55295: 1
avg=33792: 1

min=63744: 1
max=64975: 1
avg=64359.5: 1

min=65008: 1
max=65533: 1
avg=65270.5: 1

min=65536: 1
max=983039: 1
avg=524287.5: 1
