--TEST--
Function -- glob
--SKIPIF--
<?php if (!is_writable('.')) { echo 'skip'; } ?>
--FILE--
<?php

include('PHP/Compat/Function/glob.php');

/**
 * Delete a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.3
 * @link        http://aidanlister.com/repos/v/function.rmdirr.php
 * @param       string   $dirname    Directory to delete
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }

    // Simple delete for a file
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }

    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Recurse
        rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
    }

    // Clean up
    $dir->close();
    return rmdir($dirname);
}

// create test directory
$base_dir = 'php_compat_test_glob_' . md5(uniqid(rand(), true));
mkdir($base_dir, 0777);
chdir($base_dir);

// create test contents
touch('abc.php');
touch('abcd.php');
touch('abc.jpg');
touch('abe.jpg');
touch('aba.jpg');
touch('abd.jpg');
touch('abcd.jpg');
touch('abcc.jpg');
touch('abce.jpg');
touch('abc.png');
touch('abcd.png');
touch('abc.exe');
touch('abcd.exe');
touch('foo\\?bar');

mkdir('foo', 0777);
chdir('foo');
    touch('abc.php');
    touch('abcd.php');
    touch('abc.jpg');
    touch('abcd.jpg');
    touch('abc.png');
    touch('abcd.png');
    touch('abc.exe');
    touch('abcd.exe');

    mkdir('bar', 0777);
    chdir('bar');
        touch('abc.php');
        touch('abcd.php');
        touch('abc.jpg');
        touch('abcd.jpg');
        touch('abc.png');
        touch('abcd.png');
        touch('abc.exe');
        touch('abcd.exe');
        chdir('..');

    mkdir('baz', 0777);
    chdir('baz');
        touch('abc.php');
        touch('abcd.php');
        touch('abc.jpg');
        touch('abcd.jpg');
        chdir('../..');

mkdir('baz', 0777);
chdir('baz');
    touch('abc.php');
    touch('abcd.php');
    touch('abc.jpg');
    touch('abcd.jpg');

    mkdir('bar', 0777);
    chdir('bar');
        touch('abc.php');
        touch('abcd.php');
        touch('abc.jpg');
        touch('abcd.jpg');
        chdir('../..');
        
mkdir('cat', 0777);
chdir('cat');
    touch('abc.php');
    touch('abcd.php');
    touch('abc.jpg');
    touch('abcd.jpg');

    mkdir('bar', 0777);
    chdir('bar');
        touch('abc.php');
        touch('abcd.php');
        touch('abc.jpg');
        touch('abcd.jpg');
        chdir('../..');

// test patterns
$tests = array(
    0 => array(
        'none',
        'foo',
        'ab[cd].jpg',
        'foo*',
        '???/*',
        '*foo*',
        '*/abc.*',
        'foo/*/abc.*',
        'foo/*/*'
    ),
    GLOB_BRACE => array(
        'GLOB_BRACE',
        'a*.{php,jpg}',
        'foo/a*.{php,jpg}',
        'foo/a*.{p{hp,ng},jpg}'
    ),
    (GLOB_BRACE | GLOB_NOSORT) => array(
        'GLOB_BRACE | GLOB_NOSORT',
        'a*.{php,jpg}',
        'foo/a*.{php,jpg}',
        'foo/a*.{p{hp,ng},jpg}'
    ),
    GLOB_NOSORT => array(
        'GLOB_NOSORT',
        '*/*'
    ),
    GLOB_ONLYDIR => array(
        'GLOB_ONLYDIR',
        '*',
        'foo/*'
    ),
    GLOB_MARK => array(
        'GLOB_MARK',
        'foo/*'
    ),
    GLOB_NOESCAPE => array(
        'GLOB_NOESCAPE',
        'foo\\?bar'
    ),
    GLOB_NOCHECK => array(
        'GLOB_NOCHECK',
        'foo/khsgkhgjhgla'
    )
);


foreach ($tests as $flags => $patterns) {
    $ftext = array_shift($patterns);
    foreach ($patterns as $pattern) {
        $compat = php_compat_glob($pattern, $flags);
        if ($flags & GLOB_NOSORT) {
            natsort($compat);
            $compat = array_values($compat);
        }
        echo "Flags: $ftext\nPattern: '$pattern'\n";
        var_dump($compat);
        echo "\n";
    }
}

rmdirr($base_dir);

?>
--EXPECT--
Flags: none
Pattern: 'foo'
array(1) {
  [0]=>
  string(3) "foo"
}

Flags: none
Pattern: 'ab[cd].jpg'
array(2) {
  [0]=>
  string(7) "abc.jpg"
  [1]=>
  string(7) "abd.jpg"
}

Flags: none
Pattern: 'foo*'
array(2) {
  [0]=>
  string(3) "foo"
  [1]=>
  string(8) "foo\?bar"
}

Flags: none
Pattern: '???/*'
array(20) {
  [0]=>
  string(11) "baz/abc.jpg"
  [1]=>
  string(11) "baz/abc.php"
  [2]=>
  string(12) "baz/abcd.jpg"
  [3]=>
  string(12) "baz/abcd.php"
  [4]=>
  string(7) "baz/bar"
  [5]=>
  string(11) "cat/abc.jpg"
  [6]=>
  string(11) "cat/abc.php"
  [7]=>
  string(12) "cat/abcd.jpg"
  [8]=>
  string(12) "cat/abcd.php"
  [9]=>
  string(7) "cat/bar"
  [10]=>
  string(11) "foo/abc.exe"
  [11]=>
  string(11) "foo/abc.jpg"
  [12]=>
  string(11) "foo/abc.php"
  [13]=>
  string(11) "foo/abc.png"
  [14]=>
  string(12) "foo/abcd.exe"
  [15]=>
  string(12) "foo/abcd.jpg"
  [16]=>
  string(12) "foo/abcd.php"
  [17]=>
  string(12) "foo/abcd.png"
  [18]=>
  string(7) "foo/bar"
  [19]=>
  string(7) "foo/baz"
}

Flags: none
Pattern: '*foo*'
array(2) {
  [0]=>
  string(3) "foo"
  [1]=>
  string(8) "foo\?bar"
}

Flags: none
Pattern: '*/abc.*'
array(8) {
  [0]=>
  string(11) "baz/abc.jpg"
  [1]=>
  string(11) "baz/abc.php"
  [2]=>
  string(11) "cat/abc.jpg"
  [3]=>
  string(11) "cat/abc.php"
  [4]=>
  string(11) "foo/abc.exe"
  [5]=>
  string(11) "foo/abc.jpg"
  [6]=>
  string(11) "foo/abc.php"
  [7]=>
  string(11) "foo/abc.png"
}

Flags: none
Pattern: 'foo/*/abc.*'
array(6) {
  [0]=>
  string(15) "foo/bar/abc.exe"
  [1]=>
  string(15) "foo/bar/abc.jpg"
  [2]=>
  string(15) "foo/bar/abc.php"
  [3]=>
  string(15) "foo/bar/abc.png"
  [4]=>
  string(15) "foo/baz/abc.jpg"
  [5]=>
  string(15) "foo/baz/abc.php"
}

Flags: none
Pattern: 'foo/*/*'
array(12) {
  [0]=>
  string(15) "foo/bar/abc.exe"
  [1]=>
  string(15) "foo/bar/abc.jpg"
  [2]=>
  string(15) "foo/bar/abc.php"
  [3]=>
  string(15) "foo/bar/abc.png"
  [4]=>
  string(16) "foo/bar/abcd.exe"
  [5]=>
  string(16) "foo/bar/abcd.jpg"
  [6]=>
  string(16) "foo/bar/abcd.php"
  [7]=>
  string(16) "foo/bar/abcd.png"
  [8]=>
  string(15) "foo/baz/abc.jpg"
  [9]=>
  string(15) "foo/baz/abc.php"
  [10]=>
  string(16) "foo/baz/abcd.jpg"
  [11]=>
  string(16) "foo/baz/abcd.php"
}

Flags: GLOB_BRACE
Pattern: 'a*.{php,jpg}'
array(9) {
  [0]=>
  string(7) "abc.php"
  [1]=>
  string(8) "abcd.php"
  [2]=>
  string(7) "aba.jpg"
  [3]=>
  string(7) "abc.jpg"
  [4]=>
  string(8) "abcc.jpg"
  [5]=>
  string(8) "abcd.jpg"
  [6]=>
  string(8) "abce.jpg"
  [7]=>
  string(7) "abd.jpg"
  [8]=>
  string(7) "abe.jpg"
}

Flags: GLOB_BRACE
Pattern: 'foo/a*.{php,jpg}'
array(4) {
  [0]=>
  string(11) "foo/abc.php"
  [1]=>
  string(12) "foo/abcd.php"
  [2]=>
  string(11) "foo/abc.jpg"
  [3]=>
  string(12) "foo/abcd.jpg"
}

Flags: GLOB_BRACE
Pattern: 'foo/a*.{p{hp,ng},jpg}'
array(6) {
  [0]=>
  string(11) "foo/abc.php"
  [1]=>
  string(12) "foo/abcd.php"
  [2]=>
  string(11) "foo/abc.png"
  [3]=>
  string(12) "foo/abcd.png"
  [4]=>
  string(11) "foo/abc.jpg"
  [5]=>
  string(12) "foo/abcd.jpg"
}

Flags: GLOB_BRACE | GLOB_NOSORT
Pattern: 'a*.{php,jpg}'
array(9) {
  [0]=>
  string(7) "aba.jpg"
  [1]=>
  string(7) "abc.jpg"
  [2]=>
  string(7) "abc.php"
  [3]=>
  string(8) "abcc.jpg"
  [4]=>
  string(8) "abcd.jpg"
  [5]=>
  string(8) "abcd.php"
  [6]=>
  string(8) "abce.jpg"
  [7]=>
  string(7) "abd.jpg"
  [8]=>
  string(7) "abe.jpg"
}

Flags: GLOB_BRACE | GLOB_NOSORT
Pattern: 'foo/a*.{php,jpg}'
array(4) {
  [0]=>
  string(11) "foo/abc.jpg"
  [1]=>
  string(11) "foo/abc.php"
  [2]=>
  string(12) "foo/abcd.jpg"
  [3]=>
  string(12) "foo/abcd.php"
}

Flags: GLOB_BRACE | GLOB_NOSORT
Pattern: 'foo/a*.{p{hp,ng},jpg}'
array(6) {
  [0]=>
  string(11) "foo/abc.jpg"
  [1]=>
  string(11) "foo/abc.php"
  [2]=>
  string(11) "foo/abc.png"
  [3]=>
  string(12) "foo/abcd.jpg"
  [4]=>
  string(12) "foo/abcd.php"
  [5]=>
  string(12) "foo/abcd.png"
}

Flags: GLOB_NOSORT
Pattern: '*/*'
array(20) {
  [0]=>
  string(11) "baz/abc.jpg"
  [1]=>
  string(11) "baz/abc.php"
  [2]=>
  string(12) "baz/abcd.jpg"
  [3]=>
  string(12) "baz/abcd.php"
  [4]=>
  string(7) "baz/bar"
  [5]=>
  string(11) "cat/abc.jpg"
  [6]=>
  string(11) "cat/abc.php"
  [7]=>
  string(12) "cat/abcd.jpg"
  [8]=>
  string(12) "cat/abcd.php"
  [9]=>
  string(7) "cat/bar"
  [10]=>
  string(11) "foo/abc.exe"
  [11]=>
  string(11) "foo/abc.jpg"
  [12]=>
  string(11) "foo/abc.php"
  [13]=>
  string(11) "foo/abc.png"
  [14]=>
  string(12) "foo/abcd.exe"
  [15]=>
  string(12) "foo/abcd.jpg"
  [16]=>
  string(12) "foo/abcd.php"
  [17]=>
  string(12) "foo/abcd.png"
  [18]=>
  string(7) "foo/bar"
  [19]=>
  string(7) "foo/baz"
}

Flags: GLOB_ONLYDIR
Pattern: '*'
array(3) {
  [0]=>
  string(3) "baz"
  [1]=>
  string(3) "cat"
  [2]=>
  string(3) "foo"
}

Flags: GLOB_ONLYDIR
Pattern: 'foo/*'
array(2) {
  [0]=>
  string(7) "foo/bar"
  [1]=>
  string(7) "foo/baz"
}

Flags: GLOB_MARK
Pattern: 'foo/*'
array(10) {
  [0]=>
  string(11) "foo/abc.exe"
  [1]=>
  string(11) "foo/abc.jpg"
  [2]=>
  string(11) "foo/abc.php"
  [3]=>
  string(11) "foo/abc.png"
  [4]=>
  string(12) "foo/abcd.exe"
  [5]=>
  string(12) "foo/abcd.jpg"
  [6]=>
  string(12) "foo/abcd.php"
  [7]=>
  string(12) "foo/abcd.png"
  [8]=>
  string(8) "foo/bar/"
  [9]=>
  string(8) "foo/baz/"
}

Flags: GLOB_NOESCAPE
Pattern: 'foo\?bar'
array(1) {
  [0]=>
  string(8) "foo\?bar"
}

Flags: GLOB_NOCHECK
Pattern: 'foo/khsgkhgjhgla'
array(1) {
  [0]=>
  string(16) "foo/khsgkhgjhgla"
}
