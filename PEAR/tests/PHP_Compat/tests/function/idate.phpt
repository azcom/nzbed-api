--TEST--
Function -- idate
--FILE--
<?php
require_once 'PHP/Compat/Function/idate.php';

date_default_timezone_set("UTC");

$tests = array(
    'B',    // OK
    'd',    // ...
    'h',
    'H',
    'i',
    'I',    // "2009-01-24 11:39:24" / UTC does not have DST
    'L',    // "2009-01-24 11:39:24" / is not a leap year
    'm',
    's',
    't',
    'U',
    'w',
    'W',
    'y',
    'Y',
    'z',    // ...
    'Z',    // OK

    'foo',  // NOK
    '',     // NOK
    '!',    // NOK
    '\\'    // NOK
);

function ehandler($no, $str)
{
    echo $str . " ";
}
set_error_handler('ehandler');

$time = strtotime("2009-01-24 11:39:24");

foreach ($tests as $v) {
    echo 'testing: ';
    var_dump($v);
    echo "    result: ";
    $res = idate($v, $time);
    if (!$res) {
        var_dump($res);
    } else {
        echo "> 0\n";
    }
    echo "\n";
}

restore_error_handler();
?>
--EXPECT--
testing: string(1) "B"
    result: > 0

testing: string(1) "d"
    result: > 0

testing: string(1) "h"
    result: > 0

testing: string(1) "H"
    result: > 0

testing: string(1) "i"
    result: > 0

testing: string(1) "I"
    result: int(0)

testing: string(1) "L"
    result: int(0)

testing: string(1) "m"
    result: > 0

testing: string(1) "s"
    result: > 0

testing: string(1) "t"
    result: > 0

testing: string(1) "U"
    result: > 0

testing: string(1) "w"
    result: > 0

testing: string(1) "W"
    result: > 0

testing: string(1) "y"
    result: > 0

testing: string(1) "Y"
    result: > 0

testing: string(1) "z"
    result: > 0

testing: string(1) "Z"
    result: int(0)

testing: string(3) "foo"
    result: idate(): idate format is one char bool(false)

testing: string(0) ""
    result: idate(): idate format is one char bool(false)

testing: string(1) "!"
    result: idate(): Unrecognized date format token. bool(false)

testing: string(1) "\"
    result: idate(): Unrecognized date format token. bool(false)
