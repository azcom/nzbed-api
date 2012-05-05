--TEST--
Function -- http_build_query
--FILE--
<?php
require_once 'PHP/Compat/Function/http_build_query.php';

// With an empty separator (doing this after giving it a value with ini_set has no effect)
ini_set('arg_separator.output', '');
$data = array('foo', 'bar', 'baz', 'boom', 'cow' => 'milk', 'php' =>'hypertext processor');
echo php_compat_http_build_query($data, 'flags_'), "\n";

// Ini
ini_set('arg_separator.output', '*');

// Simple
$data = array('foo'=>'bar',
             'baz'=>'boom',
             'cow'=>'milk',
             'php'=>'hypertext processor');

echo php_compat_http_build_query($data), "\n";


// With an object
class myClass {
    var $foo;
    var $baz;

    function myClass()
    {
        $this->foo = 'bar';
        $this->baz = 'boom';
    }
}

$data = new myClass();
echo php_compat_http_build_query($data), "\n";


// With numerically indexed elements
$data = array('foo', 'bar', 'baz', 'boom', 'cow' => 'milk', 'php' =>'hypertext processor');
echo php_compat_http_build_query($data), "\n";
echo php_compat_http_build_query($data, 'myvar_'), "\n";


// With a complex array
$data = array('user' => array(
                    'name' => 'Bob Smith',
                    'age' => 47,
                    'sex' => 'M',
                    'dob' => '5/12/1956'),
             'pastimes' => array(
                    'golf',
                    'opera',
                    'poker',
                    'rap'),
             'children' => array(
                    'bobby' => array(
                        'age' => 12,
                        'sex' => 'M'),
                     'sally' => array(
                        'age' => 8,
                        'sex'=>'F')),
             'CEO');

echo php_compat_http_build_query($data, 'flags_'), "\n";

// With a nested object
$data = array('foo' => new myClass());
echo php_compat_http_build_query($data, 'flags_'), "\n";

// With a key which evaluates to false
$data = array('' => array('hello world'), 'foo' => 'bar');
echo php_compat_http_build_query($data, 'flags_'), "\n";

// With a separator which evaluates to false
ini_set('arg_separator.output', '0');
echo php_compat_http_build_query($data, 'flags_'), "\n";

// With a resource
$data = array('foo' => null, 'bar' => fopen('php://input', 'r'));
var_dump(php_compat_http_build_query($data, 'flags_'));

// With a null value
$data = array('foo' => null, 'bar' => 1);
echo php_compat_http_build_query($data, 'flags_');

?>
--EXPECT--
flags_0=foo&flags_1=bar&flags_2=baz&flags_3=boom&cow=milk&php=hypertext+processor
foo=bar*baz=boom*cow=milk*php=hypertext+processor
foo=bar*baz=boom
0=foo*1=bar*2=baz*3=boom*cow=milk*php=hypertext+processor
myvar_0=foo*myvar_1=bar*myvar_2=baz*myvar_3=boom*cow=milk*php=hypertext+processor
user[name]=Bob+Smith*user[age]=47*user[sex]=M*user[dob]=5%2F12%2F1956*pastimes[0]=golf*pastimes[1]=opera*pastimes[2]=poker*pastimes[3]=rap*children[bobby][age]=12*children[bobby][sex]=M*children[sally][age]=8*children[sally][sex]=F*flags_0=CEO
foo[foo]=bar*foo[baz]=boom
[0]=hello+world*foo=bar
[0]=hello+world0foo=bar
NULL
bar=1