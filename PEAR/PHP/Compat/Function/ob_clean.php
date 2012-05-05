<?php
/**
 * Replace ob_clean()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.ob_clean
 * @author      Aidan Lister <aidan@php.net>
 * @author      Thiemo M�ttig (http://maettig.com/)
 * @version     $Revision: 269597 $
 * @since       PHP 4.2.0
 * @require     PHP 4.0.0 (user_error)
 */
function php_compat_ob_clean()
{
    if (@ob_end_clean()) {
        return ob_start();
    }

    user_error("ob_clean() failed to delete buffer. No buffer to delete.", E_USER_NOTICE);

    return false;

}


// Define
if (!function_exists('ob_clean')) {
    function ob_clean()
    {
        return php_compat_ob_clean();
    }
}
