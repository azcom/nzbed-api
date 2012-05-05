--TEST--
Uploaded file is too large (upload_max_filesize)
--INI--
upload_max_filesize=5
--POST--
MAX_FILE_SIZE=100000
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
Notice: upload_max_filesize of 5 bytes exceeded - file [userfile=10b] not saved in Unknown on line 0
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
  string(0) ""
  ["size"]=>
  int(0)
  ["type"]=>
  string(0) ""
  ["error"]=>
  string(9) "TOO_LARGE"
}
