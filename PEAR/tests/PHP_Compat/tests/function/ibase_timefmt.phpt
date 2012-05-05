--TEST--
Function -- ibase_timefmt
--SKIPIF--
<?php if (!extension_loaded('ibase')) { echo 'Skip, cannot test ibase_timefmt() without ibase extension'; } ?>
--FILE--
<?php
require_once 'PHP/Compat/Function/ibase_timefmt.php';

$format = '%D';
$formatTime = '%T';

$ret = php_compat_ibase_timefmt($format);
echo var_export($ret, true), " ", ini_get("ibase.dateformat"), "\n";

$ret = php_compat_ibase_timefmt($format, IBASE_TIMESTAMP);
echo var_export($ret, true), " ", ini_get("ibase.dateformat"), "\n";

$ret = php_compat_ibase_timefmt($format, IBASE_DATE);
echo var_export($ret, true), " ", ini_get("ibase.dateformat"), "\n";

$ret = php_compat_ibase_timefmt($formatTime, IBASE_TIME);
echo var_export($ret, true), " ", ini_get("ibase.timeformat"), "\n";

$ret = php_compat_ibase_timefmt('%d', 'invalid column type');
echo var_export($ret, true), " ", ini_get("ibase.dateformat"), "\n";
echo var_export($ret, true), " ", ini_get("ibase.timeformat"), "\n";

?>
--EXPECT--
true %D
true %D
true %D
true %T
false %D
false %T