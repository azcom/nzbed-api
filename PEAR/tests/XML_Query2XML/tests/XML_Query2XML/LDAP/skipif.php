<?php
/**
 * This is included from unit tests to skip the test if Net/LDAP.php or the LDAP
 * directory itself is not available.
 *
 * PHP version 5
 *
 * @category  XML
 * @package   XML_Query2XML
 * @author    Lukas Feiler <lukas.feiler@lukasfeiler.com>
 * @copyright 2007 Lukas Feiler
 * @license   http://www.gnu.org/copyleft/lesser.html  LGPL Version 2.1
 * @version   CVS: $Id: skipif.php,v 1.2 2008/04/18 23:52:52 lukasfeiler Exp $
 * @link      http://pear.php.net/package/XML_Query2XML
 * @access    private
 */

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
    if (!@include_once 'Net/LDAP2.php') {
        print 'skip could not find Net/LDAP2.php';
        exit;
    } else {
        include_once dirname(dirname(__FILE__)) . '/settings.php';
        $ldap = Net_LDAP2::connect($ldapConfig);
        if (PEAR::isError($ldap)) {
            print 'skip could not connect to LDAP directory';
            exit;
        }
    }
} else {
    if (!@include_once 'Net/LDAP.php') {
        print 'skip could not find Net/LDAP.php';
        exit;
    } else {
        include_once dirname(dirname(__FILE__)) . '/settings.php';
        $ldap = Net_LDAP::connect($ldapConfig);
        if (PEAR::isError($ldap)) {
            print 'skip could not connect to LDAP directory';
            exit;
        }
    }
}
?>