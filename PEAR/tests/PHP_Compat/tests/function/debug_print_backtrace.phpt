--TEST--
Function -- debug_print_backtrace
--FILE--
<?php
require_once 'PHP/Compat/Function/debug_print_backtrace.php';

class OuterClass {
    function foo() {
        $inner = new InnerClass;
        $inner->bar();
    }
}
class InnerClass {
    function bar() {
        $this->baz();
    }
    function baz() {
        php_compat_debug_print_backtrace();
    }
}
function outerFunction() {
    OuterClass::Foo();
}
class myClass
{
    function myClass()
    {
    }
}
function debug($var, $val)
{
    echo "Variable: $var\nValue: ";
    if (is_array($val) || is_object($val) || is_resource($val)) {
        print_r($val);
    } else {
        echo "\n$val\n";
    }
    php_compat_debug_print_backtrace();
    echo "***\n";
}

ob_start();

// tests
outerFunction();
echo "\n";
$host = 'host';
call_user_func_array('debug', array("host", $host));
$myClass = new myClass();
call_user_func_array('debug', array("myClass", $myClass));
$fp = fopen('php://stderr', 'wb');
call_user_func_array('debug', array("fp", $fp));
fclose($fp);

$output = ob_get_contents();
ob_end_clean();
$output = preg_replace('#\[[^\]]+\.php#', '', $output);
echo $output;
?>
--EXPECT--
#0  InnerClass->baz() called at :12]
#1  InnerClass->bar() called at :7]
#2  OuterClass::foo() called at :19]
#3  outerFunction() called at :42]

Variable: host
Value: 
host
#0  debug(host, host) called at [(null):0]
#1  call_user_func_array(debug, Array ( [0] => host [1] => host ) ) called at :45]
***
Variable: myClass
Value: myClass Object
(
)
#0  debug(myClass, myClass Object ( ) ) called at [(null):0]
#1  call_user_func_array(debug, Array ( [0] => myClass [1] => myClass Object ( ) ) ) called at :47]
***
Variable: fp
Value: Resource id #6#0  debug(fp, Resource id #6) called at [(null):0]
#1  call_user_func_array(debug, Array ( [0] => fp [1] => Resource id #6 ) ) called at :49]
***