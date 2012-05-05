<?php
/**
 * Replace setrawcookie()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2009 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.setrawcookie
 * @author      Stephan Schmidt <schst@php.net>
 * @version     $Revision: 279662 $
 * @since       PHP 5.2.0 (Added optional httponly parameter)
 * @require     PHP 3 (setcookie)
 */
function php_compat_setrawcookie($name, $value, $expire=0, $path=false, $domain=false, $secure=false, $httponly=false)
{    
    // Following the idea on Matt Mecham's blog
    // http://blog.mattmecham.com/archives/2006/09/http_only_cookies_without_php.html
    $domain = ($httponly === true) ? $domain . '; HttpOnly' : $domain;
    
    // This should probably set a cookie using header() manually so we can avoid escaping
    setcookie($name, $value, $expire, $path, $domain, $secure);
}

// Define
if (!function_exists('setrawcookie')) {
    function setrawcookie($name, $value, $expire=0, $path=false, $domain=false, $secure=false, $httponly=false)
    {
        return php_compat_setrawcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }
}
