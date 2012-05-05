<?php
/**This is included from unit tests to skip the test if ADOdb is not available.
*
* LICENSE:
* This source file is subject to version 2.1 of the LGPL
* that is bundled with this package in the file LICENSE.
*
* COPYRIGHT:
* Empowered Media
* http://www.empoweredmedia.com
* 481 Eighth Avenue Suite 1530
* New York, NY 10001
*
* @copyright Empowered Media 2006
* @license http://www.gnu.org/copyleft/lesser.html  LGPL Version 2.1
* @author Lukas Feiler <lukas.feiler@lukasfeiler.com>
* @package XML_Query2XML
* @version $Id: ADOdbException_skipif.php,v 1.1 2008/04/18 23:50:25 lukasfeiler Exp $
*/

if (!@include_once 'adodb/adodb.inc.php') {
    print 'skip could not find adodb/adodb.inc.php';
    exit;
} elseif (!@include_once 'adodb/adodb-exceptions.inc.php') {
    print 'skip could not find adodb/adodb-exceptions.inc.php';
    exit;
} else {
    require_once dirname(dirname(__FILE__)) . '/settings.php';
    $db = false;
    try {
        $db = @NewADOConnection(DSN);
    } catch (Exception $e) {}
    if (!$db) {
        print 'skip could not connect using DSN ' . DSN;
        exit;
    }
}
?>