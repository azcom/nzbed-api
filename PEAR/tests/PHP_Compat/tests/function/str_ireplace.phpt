--TEST--
Function -- str_ireplace
--FILE--
<?php
require_once 'PHP/Compat/Function/str_ireplace.php';

//
// Simple
//

$search = '{object}';
$replace = 'fence';
$subject = 'The dog jumped over the {object}';

echo php_compat_str_ireplace($search, $replace, $subject), "\n";

//
// Test 1: With subject as array
//

// As a full array
$search = '{SUBJECT}';
$replace = 'Lady';
$subject = array('A {subject}', 'The {subject}', 'My {subject}');
print_r(php_compat_str_ireplace($search, $replace, $subject));

// As a single array
$search = '{SUBJECT}';
$replace = 'Lady';
$subject = array('The dog jumped over the {object}');
print_r(php_compat_str_ireplace($search, $replace, $subject));


//
// Test 2: Search as string, replace as array
//

$search = '{object}';
$replace = array('cat', 'dog', 'tiger');
$subject = 'The dog jumped over the {object}';
// Supress the error, no way of knowing how it'll turn out on the users machine
echo @php_compat_str_ireplace($search, $replace, $subject), "\n";


//
// Test 3: Search as array, Replace as string
//

$search = array('{ANIMAL}', '{OBJECT}', '{THING}');
$replace = 'frog';
$subject = 'The {animal} jumped over the {object} and the {thing}...';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";


//
// Test 4: Search and Replace as arrays
//

// Simple
$search = array('{ANIMAL}', '{OBJECT}');
$replace = array('frog', 'gate');
$subject = 'The {animal} jumped over the {object}';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";

// More in search
$search = array('{ANIMAL}', '{OBJECT}', '{THING}');
$replace = array('frog', 'gate');
$subject = 'The {animal} jumped over the {object} and the {thing}...';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";

// More in replace
$search = array('{ANIMAL}', '{OBJECT}');
$replace = array('frog', 'gate', 'door');
$subject = 'The {animal} jumped over the {object} and the {thing}...';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";


//
// Test 5: All arrays
//

$search = array('{ANIMAL}', '{OBJECT}', '{THING}');
$replace = array('frog', 'gate', 'beer');
$subject = array('A {animal}', 'The {object}', 'My {thing}');
print_r(php_compat_str_ireplace($search, $replace, $subject));


//
// Test 6: PCRE pattern syntax in search
//
$search = '$Price';
$replace = '0.99';
$subject = 'The cost is $price';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";


//
// Test 7: PCRE replacement syntax in replacement
//
$search = 'Price';
$replace = '$0.99';
$subject = 'The cost is price';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";


//
// Test 8: escaped PCRE replacement syntax in replacement
//
$search = 'Price';
$replace = '\$0.99 \$1 \$11';
$subject = 'The cost is price';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";


//
// Test 9: fake escaped PCRE replacement syntax in replacement
//
$search = 'Price';
$replace = '\\\\$0.99';
$subject = 'The cost is price';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";


//
// Test 10: mixture of backslashes
//
$search = 'Price';
$replace = '\\$0\.\\\\9\\\9';
$subject = 'The cost is price';
echo php_compat_str_ireplace($search, $replace, $subject), "\n";

?>
--EXPECT--
The dog jumped over the fence
Array
(
    [0] => A Lady
    [1] => The Lady
    [2] => My Lady
)
Array
(
    [0] => The dog jumped over the {object}
)
The dog jumped over the Array
The frog jumped over the frog and the frog...
The frog jumped over the gate
The frog jumped over the gate and the ...
The frog jumped over the gate and the {thing}...
Array
(
    [0] => A frog
    [1] => The gate
    [2] => My beer
)
The cost is 0.99
The cost is $0.99
The cost is \$0.99 \$1 \$11
The cost is \\$0.99
The cost is \$0\.\\9\\9
