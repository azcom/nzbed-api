--TEST--
Upload a file and rename it before moving
--UPLOAD--
userfile=files/10b
--FILE--
<?php
require_once 'HTTP/Upload.php';
$up = new HTTP_Upload();
$file = $up->getFiles('userfile');
echo "Valid: ";   var_dump($file->isValid());
echo "Missing: "; var_dump($file->isMissing());
echo "Error: ";   var_dump($file->isError());

$newname = $file->setName('my10b');
var_dump($newname);

$tmp = sys_get_temp_dir();
$name = $file->moveTo($tmp);
echo 'MoveError: '; var_dump(PEAR::isError($name));
var_dump($name);
var_dump($file->getProp());
var_dump(file_exists($tmp . '/my10b'));
?>
--EXPECTF--
Valid: bool(true)
Missing: bool(false)
Error: bool(false)
string(5) "my10b"
MoveError: bool(false)
string(5) "my10b"
array(8) {
  ["real"]=>
  string(3) "10b"
  ["name"]=>
  string(5) "my10b"
  ["form_name"]=>
  string(8) "userfile"
  ["ext"]=>
  NULL
  ["tmp_name"]=>
  string(28) "%s"
  ["size"]=>
  int(10)
  ["type"]=>
  string(10) "text/plain"
  ["error"]=>
  NULL
}
bool(true)
