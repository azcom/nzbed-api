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
    $validRanges[] = array(hexdec('370'),   hexdec('37D'));
    $validRanges[] = array(hexdec('37F'),   hexdec('1FFF'));
    $validRanges[] = array(hexdec('200C'),  hexdec('200D'));
    $validRanges[] = array(hexdec('2070'),  hexdec('218F'));
    $validRanges[] = array(hexdec('2C00'),  hexdec('2FEF'));
    $validRanges[] = array(hexdec('3001'),  hexdec('D7FF'));
    $validRanges[] = array(hexdec('F900'),  hexdec('FDCF'));
    $validRanges[] = array(hexdec('FDF0'),  hexdec('FFFD'));
    $validRanges[] = array(hexdec('10000'), hexdec('EFFFF'));
    
    for ($i = 0; $i < count($validRanges); $i++) {
        $min = $validRanges[$i][1] + 1;
        if (!isset($validRanges[$i+1])) {
            $max = hexdec('FFFFF');
        } else {
            $max = $validRanges[$i+1][0];
        }
        
        for ($char = $min; $char < $max; $char++) {
            $expectedHex = dechex($char);
            if (strlen($expectedHex) < 4) {
                $expectedHex = str_pad($expectedHex, 4, '0', STR_PAD_LEFT);
            } elseif (strlen($expectedHex) > 4 && strlen($expectedHex) < 8) {
                $expectedHex = str_pad($expectedHex, 8, '0', STR_PAD_LEFT);
            }
            if (('_x' . $expectedHex . '_') !==
                XML_Query2XML_ISO9075Mapper::map(I18N_UnicodeString::unicodeCharToUtf8($char))) {
                print $expectedHex . ': UNEXPECTED RESULT';
            }
        }
    }
    print 'end';
?>
--EXPECT--
end
