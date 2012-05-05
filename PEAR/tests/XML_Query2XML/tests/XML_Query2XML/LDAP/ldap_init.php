<?php
/**
 * This is included from unit tests to initialize an LDAP connection.
 *
 * PHP version 5
 *
 * @category  XML
 * @package   XML_Query2XML
 * @author    Lukas Feiler <lukas.feiler@lukasfeiler.com>
 * @copyright 2007 Lukas Feiler
 * @license   http://www.gnu.org/copyleft/lesser.html  LGPL Version 2.1
 * @version   CVS: $Id: ldap_init.php,v 1.2 2008/04/18 23:52:52 lukasfeiler Exp $
 * @link      http://pear.php.net/package/XML_Query2XML
 * @access    private
 */

require_once dirname(dirname(__FILE__)) . '/settings.php';
if (!defined('LDAP_LAYER')) {
    if (getenv('PHP_PEAR_XML_QUERY2XML_TEST_LDAPLAYER') != '') {
        define('LDAP_LAYER', getenv('PHP_PEAR_XML_QUERY2XML_TEST_LDAPLAYER'));
    } else {
        if (@include_once 'Net/LDAP2.php') {
            define('LDAP_LAYER', 'LDAP2');
        } else {
            define('LDAP_LAYER', 'LDAP');
        }
    }
}

if (LDAP_LAYER == 'LDAP2') {
    require_once 'Net/LDAP2.php';
    $ldap = Net_LDAP2::connect($ldapConfig);
} else {
    require_once 'Net/LDAP.php';
    $ldap = Net_LDAP::connect($ldapConfig);
}

class XML_Query2XML_TESTS_LDAP_Helper
{
    public function LDAP_Filter_factory($ldap, $attr_name, $match, $value = '', $escape = true)
    {
        if ($ldap instanceof Net_LDAP2) {
            return Net_LDAP2_Filter::create($attr_name, $match, $value, $escape);
        } else {
            return Net_LDAP_Filter::create($attr_name, $match, $value, $escape);
        }
    }
}
?>