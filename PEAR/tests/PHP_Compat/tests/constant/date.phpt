--TEST--
Constant -- DATE
--FILE--
<?php
require_once ('PHP/Compat.php');
PHP_Compat::loadConstant('DATE');

if (defined('DATE_ATOM') && DATE_ATOM === 'Y-m-d\TH:i:sP') { echo "pass\n"; }
if (defined('DATE_COOKIE') && DATE_COOKIE === 'l, d-M-y H:i:s T') { echo "pass\n"; }
if (defined('DATE_ISO8601') && DATE_ISO8601 === 'Y-m-d\TH:i:sO') { echo "pass\n"; }
if (defined('DATE_RFC822') && DATE_RFC822 === 'D, d M y H:i:s O') { echo "pass\n"; }
if (defined('DATE_RFC850') && DATE_RFC850 === 'l, d-M-y H:i:s T') { echo "pass\n"; }
if (defined('DATE_RFC1036') && DATE_RFC1036 === 'D, d M y H:i:s O') { echo "pass\n"; }
if (defined('DATE_RFC1123') && DATE_RFC1123 === 'D, d M Y H:i:s O') { echo "pass\n"; }
if (defined('DATE_RFC2822') && DATE_RFC2822 === 'D, d M Y H:i:s O') { echo "pass\n"; }
if (defined('DATE_RSS') && DATE_RSS === 'D, d M Y H:i:s O') { echo "pass\n"; }
if (defined('DATE_W3C') && DATE_W3C === 'Y-m-d\TH:i:sP') { echo "pass\n"; }

?>
--EXPECT--
pass
pass
pass
pass
pass
pass
pass
pass
pass
pass