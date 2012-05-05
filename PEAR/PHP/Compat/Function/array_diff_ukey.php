<?php
/**
 * Replace array_diff_ukey()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.array_diff_ukey
 * @author      Tom Buskens <ortega@php.net>
 * @version     $Revision: 269597 $
 * @since       PHP 5.0.2
 * @require     PHP 4.0.6 (is_callable)
 */
function php_compat_array_diff_ukey()
{
    $args = func_get_args();
    if (count($args) < 3) {
        user_error('Wrong parameter count for array_diff_ukey()', E_USER_WARNING);
        return;
    }

    // Get compare function
    $compare_func = array_pop($args);
    if (!is_callable($compare_func)) {
        if (is_array($compare_func)) {
            $compare_func = $compare_func[0].'::'.$compare_func[1];
        }
        user_error('array_diff_ukey() Not a valid callback ' .
            $compare_func, E_USER_WARNING);
        return;
    }

    // Check arrays
    $array_count = count($args);
    for ($i = 0; $i !== $array_count; $i++) {
        if (!is_array($args[$i])) {
            user_error('array_diff_ukey() Argument #' .
                ($i + 1) . ' is not an array', E_USER_WARNING);
            return;
        }
    }

    // Compare entries
    $result = $args[0];
    foreach ($args[0] as $key1 => $value1) {
        for ($i = 1; $i !== $array_count; $i++) {
            foreach ($args[$i] as $key2 => $value2) {
                if (!(call_user_func($compare_func, (string) $key1, (string) $key2))) {
                    unset($result[$key1]);
                    break 2;
                }
            }
        }
    }

    return $result;
}


// Define
if (!function_exists('array_diff_ukey')) {
    function array_diff_ukey()
    {
        $args = func_get_args();
        return call_user_func_array('php_compat_array_diff_ukey', $args);      
    }
}
