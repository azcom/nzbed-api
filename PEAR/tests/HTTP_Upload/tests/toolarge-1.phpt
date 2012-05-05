--TEST--
Uploaded file is too large (POST setting)
--POST--
MAX_FILE_SIZE=5
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

var_dump($file->getProp());
?>
--EXPECTF--
Valid: bool(false)
Missing: bool(false)
Error: bool(true)
array(8) {
  ["real"]=>
  string(3) "10b"
  ["name"]=>
  string(3) "10b"
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
  string(9) "TOO_LARGE"
}
