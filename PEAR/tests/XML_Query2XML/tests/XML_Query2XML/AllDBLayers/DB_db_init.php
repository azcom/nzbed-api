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
* @version $Id: DB_db_init.php,v 1.1 2008/04/18 23:50:25 lukasfeiler Exp $
*/

require_once dirname(dirname(__FILE__)) . '/settings.php';
require_once 'DB.php';
$db = DB::connect(DSN);
?>