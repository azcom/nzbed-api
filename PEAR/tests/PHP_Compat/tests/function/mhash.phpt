--TEST--
Function -- mhash
--FILE--
<?php
require_once 'PHP/Compat/Function/mhash.php';

$tests = array( // test vectors from RFC 2202
    array(
        'key'   => str_repeat(chr(0x0b), 16),
        'data'  => 'Hi There'
    ),
    array(
        'key'   => 'Jefe',
        'data'  => 'what do ya want for nothing?'
    ),
    array(
        'key'   => str_repeat(chr(0xAA), 16),
        'data'  => str_repeat(chr(0xDD), 50)
    ),
    array(
        'key'   => pack('H*', '0102030405060708090a0b0c0d0e0f10111213141516171819'),
        'data'  => str_repeat(chr(0xCD), 50)
    ),
    array(
        'key'   => str_repeat(chr(0x0C), 16),
        'data'  => 'Test With Truncation'
    ),
    array(
        'key'   => str_repeat(chr(0xAA), 80),
        'data'  => 'Test Using Larger Than Block-Size Key - Hash Key First'
    ),
    array(
        'key'   => str_repeat(chr(0xAA), 80),
        'data'  => 'Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data'
    ),
);

$types = array(
    'MD5'   => MHASH_MD5,
    // 'SHA1' => MHASH_SHA1,
);

foreach ($types as $name => $type) {
    foreach ($tests as $number => $test) {
        $result = php_compat_mhash($type, $test['data'], $test['key']);
        echo $name, ' ', ($number + 1), ': ', bin2hex($result), "\n";
    }
}

?>
--EXPECT--
MD5 1: 9294727a3638bb1c13f48ef8158bfc9d
MD5 2: 750c783e6ab0b503eaa86e310a5db738
MD5 3: 56be34521d144c88dbb8c733f0e8b3f6
MD5 4: 697eaf0aca3a3aea3a75164746ffaa79
MD5 5: 56461ef2342edc00f9bab995690efd4c
MD5 6: 6b1ab7fe4bd7bf8f0b62e6ce61b9d0cd
MD5 7: 6f630fad67cda0ee1fb1f562db3aa53e