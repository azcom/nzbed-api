--TEST--
Function -- is_scalar
--FILE--
<?php
require_once 'PHP/Compat/Function/is_scalar.php';

$tests = array(
    13,
    13.37,
    'foo',
    '13',
    '',
    null,
    true,
    array(),
    new stdClass
);

for ($i = 0, $testc = count($tests); $i < $testc; $i++) {
    echo "Testing: ($i) ", print_r($tests[$i], 1), "\n";
    var_dump(is_scalar($tests[$i]));
    echo "\n";
}

?>
--EXPECT--
Testing: (0) 13
bool(true)

Testing: (1) 13.37
bool(true)

Testing: (2) foo
bool(true)

Testing: (3) 13
bool(true)

Testing: (4) 
bool(true)

Testing: (5) 
bool(false)

Testing: (6) 1
bool(true)

Testing: (7) Array
(
)

bool(false)

Testing: (8) stdClass Object
(
)

bool(false)

