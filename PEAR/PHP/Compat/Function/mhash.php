<?php
if (!defined('MHASH_CRC32')) {
    define('MHASH_CRC32', 0);
}

if (!defined('MHASH_MD5')) {
    define('MHASH_MD5', 1);
}

if (!defined('MHASH_SHA1')) {
    define('MHASH_SHA1', 2);
}

if (!defined('MHASH_HAVAL256')) {
    define('MHASH_HAVAL256', 3);
}

if (!defined('MHASH_RIPEMD160')) {
    define('MHASH_RIPEMD160', 5);
}

if (!defined('MHASH_TIGER')) {
    define('MHASH_TIGER', 7);
}

if (!defined('MHASH_GOST')) {
    define('MHASH_GOST', 8);
}

if (!defined('MHASH_CRC32B')) {
    define('MHASH_CRC32B', 9);
}

if (!defined('MHASH_HAVAL192')) {
    define('MHASH_HAVAL192', 11);
}

if (!defined('MHASH_HAVAL160')) {
    define('MHASH_HAVAL160', 12);
}

if (!defined('MHASH_HAVAL128')) {
    define('MHASH_HAVAL128', 13);
}

if (!defined('MHASH_TIGER128')) {
    define('MHASH_TIGER128', 14);
}

if (!defined('MHASH_TIGER160')) {
    define('MHASH_TIGER160', 15);
}

if (!defined('MHASH_MD4')) {
    define('MHASH_MD4', 16);
}

if (!defined('MHASH_SHA256')) {
    define('MHASH_SHA256', 17);
}

if (!defined('MHASH_ADLER32')) {
    define('MHASH_ADLER32', 18);
}


/**
 * Replace mhash()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.mhash
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 269597 $
 * @since       PHP 4.1.0
 * @require     PHP 4.0.0 (user_error)
 */
function php_compat_mhash($hashtype, $data, $key = '')
{
    switch ($hashtype) {
        case MHASH_MD5:
            $key = str_pad((strlen($key) > 64 ? pack("H*", md5($key)) : $key), 64, chr(0x00));
            $k_opad = $key ^ (str_pad('', 64, chr(0x5c)));
            $k_ipad = $key ^ (str_pad('', 64, chr(0x36)));
            return pack("H*", md5($k_opad . pack("H*", md5($k_ipad .  $data))));

        default:
            return false;

        break;
    }
}


// Define
if (!function_exists('mhash')) {
    function mhash($hashtype, $data, $key = '')
    {
        return php_compat_mhash($hashtype, $data, $key = '');
    }
}
