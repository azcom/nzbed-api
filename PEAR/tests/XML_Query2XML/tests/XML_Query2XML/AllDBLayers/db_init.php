<?php
/**This is included from unit tests to initialize a DB connection.
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
* @version $Id: db_init.php,v 1.1 2008/04/18 23:50:25 lukasfeiler Exp $
*/

if (!defined('DBLAYER')) {
    if (getenv('PHP_PEAR_XML_QUERY2XML_TEST_DBLAYER') != '') {
        define('DBLAYER', getenv('PHP_PEAR_XML_QUERY2XML_TEST_DBLAYER'));
    } else {
        define('DBLAYER', 'MDB2');
    }
}

if (!@include_once DBLAYER . '_db_init.php') {
    print 'skip could not find ' . DBLAYER . '_skipif.php';
    exit;
}
?>