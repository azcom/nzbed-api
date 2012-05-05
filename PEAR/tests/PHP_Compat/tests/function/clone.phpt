--TEST--
Function -- clone
--SKIPIF--
<?php if (version_compare(PHP_VERSION, '5.0') === 1) { echo 'skip'; } ?>
--FILE--
<?php
require_once 'PHP/Compat/Function/clone.php';

// Test classes
class testclass
{
    var $foo = 'foo';
}

class testclass2
{
    var $foo = 'foo';

    function __clone()    
    {
        $this->foo = 'bar';
    }
}

class testclass3
{
    var $bar;
}

class testclass4
{
    var $foo;
    function __clone()
    {
        $this->foo = php_compat_clone($this->foo);
    }
}

class testclass5 {
    var $child;
    var $foo;
    function __clone() {
        $this->child = php_compat_clone($this->child);
        $this->child->parent = null;
        $this->child->parent = &$this;
    }
}

class testclass5child extends testclass5 {
    var $parent;
    function testclass5child()
    {
        $this->child = new stdClass;   
    }
}

// Test 1: Initial value
$aa = new testclass;
echo $aa->foo, "\n"; // foo

// Test 2: Not referenced
$bb = php_compat_clone($aa);
$bb->foo = 'baz';
echo $aa->foo, "\n"; // foo

// Test 3: __clone method
$cc = new testclass2;
echo $cc->foo, "\n"; // foo
$dd = php_compat_clone($cc);
echo $dd->foo, "\n"; // bar

// Test 4: Bug #3649
$a = new testclass3;
$a->foo =& new testclass4;
$a->foo->bar = 'hello';
$aclone = php_compat_clone($a);
$aclone->b->bar = 'goodbye';
echo $a->foo->bar, "\n";

// Test 5: Bug #7519 - clone does not return reference
$a = new testclass5;
$a->foo = 'original parent';
$a->child = new testclass5child;
$a->child->parent = &$a;
$a->child->foo = 'original child';

$b = php_compat_clone($a);
$b->foo = 'new parent';
$b->child->foo = 'new child';

echo $b->child->parent->foo, "\n"; // new parent
echo $b->foo, "\n"; // new parent
echo $a->child->parent->foo, "\n"; // original parent
echo $a->foo, "\n"; // original parent

echo $b->child->foo, "\n"; // new child
echo $b->child->parent->child->foo, "\n"; // new child
echo $a->child->foo, "\n"; // original child
echo $a->child->parent->child->foo, "\n"; // original child

?>
--EXPECT--
foo
foo
foo
bar
hello
new parent
new parent
original parent
original parent
new child
new child
original child
original child
