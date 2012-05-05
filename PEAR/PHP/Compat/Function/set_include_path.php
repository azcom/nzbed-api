<?php
/**
 * Replace set_include_path()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.set_include_path
 * @author      Stephan Schmidt <schst@php.net>
 * @version     $Revision: 269597 $
 * @since       PHP 4.3.0
 */
function php_compat_set_include_path($new_include_path)
{
    return ini_set('include_path', $new_include_path);
}


// Define
if (!function_exists('set_include_path')) {
    function set_include_path($new_include_path)
    {
        return php_compat_set_include_path($new_include_path);
    }
}
