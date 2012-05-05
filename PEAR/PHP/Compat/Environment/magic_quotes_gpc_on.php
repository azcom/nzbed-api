<?php
/**
 * Emulate environment magic_quotes_gpc=on
 *
 * See _magic_quotes_inputs.php for more details.
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/magic_quotes
 * @author      Arpad Ray <arpad@php.net>
 * @version     $Revision: 274851 $
 */

// wrap everything in a function to keep global scope clean
function php_compat_magic_quotes_gpc_on()
{
    $stripping = false;
	// Require Inputs - Assumes file is in the same directory
    require_once '_magic_quotes_inputs.php';

    if (!$magicOn || !$allWorks && !$sybaseOn) {
        $inputCount = count($inputs);
        while (list($k, $v) = each($inputs)) {
            foreach ($v as $var => $value) {
                $isArray = is_array($value);
                $order1 = $k < $inputCount;
                $escapeKeys = $magicOn
                    ? ($isArray ? $order1 : $phpLt434 || $order1 && $phpLt50)
                    : $phpLt50 || !$phpLt51 || $isArray;
                if ($escapeKeys) {
                    $tvar = addslashes($var);
                    if ($var != $tvar) {
                        $tvalue = $inputs[$k][$var];
                        $inputs[$k][$tvar] = $tvalue;
                        unset($inputs[$k][$var]);
                        $var = $tvar;
                    }
                }
                if ($isArray) {
                    $inputs[] = &$inputs[$k][$var];
                } else {
                    $inputs[$k][$var] = $magicOn ? $value : addslashes($value);
                }
            }
        }
    }
}

php_compat_magic_quotes_gpc_on();

// Register the change
//ini_set('magic_quotes_gpc', 1); // Cannot be set at runtime (bug 15532)
$GLOBALS['__PHP_Compat_ini']['magic_quotes_gpc'] = true;
