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
* @version $Id: PDO_db_init.php,v 1.1 2008/04/18 23:50:25 lukasfeiler Exp $
*/

class MyPDOStatement extends PDOStatement
{
    protected function __construct()
    {
        //parent::__construct();
    }
    
    /**Does what PDO::ATTR_FETCH_TABLE_NAMES was supposed to do.
    */
    public function fetch()
    {
        if ($record = parent::fetch()) {
            foreach ($record as $key => $value) {
                $newKey = $key;
                if (strpos($newKey, '.') !== false) {
                    $newKey = substr($newKey, strpos($newKey, '.') + 1);
                }
                $newRecord[$newKey] = $value;
            }
        } else {
            return $record;
        }
        return $newRecord;
    }
    
    /**Does what PDO::ATTR_FETCH_TABLE_NAMES was supposed to do.
    */
    public function fetchAll()
    {
        $records = parent::fetchAll();
        if (is_array($records)) {
            $newRecords = array();
            for ($i = 0; $i < count($records); $i++) {
                foreach ($records[$i] as $key => $value) {
                    $newKey = $key;
                    if (strpos($newKey, '.') !== false) {
                        $newKey = substr($newKey, strpos($newKey, '.') + 1);
                    }
                    $newRecords[$i][$newKey] = $value;
                }
            }
            return $newRecords;
        } else {
            return $records;
        }
    }
}

require_once dirname(dirname(__FILE__)) . '/settings.php';
list($protocol, $address) = split('://', DSN);
if (strpos($address, '@') === false) {
    if ($protocol == 'sqlite') {
        $protocol .= '2';
    }
    if (strpos($address, '/C:\\') === 0) {
        $address = ltrim($address, '/');
    }
    $db = new PDO($protocol . ':' . $address);
    $db->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('MyPDOStatement', array()));
} else {
    list($credentials, $address) = split('@', $address);
        if (strpos($credentials, ':') === false) {
        $username = $credentials;
        $password = '';
    } else {
        list($username, $password) = split(':', $credentials);
    }
    list($host,$database) = split('/', $address);
    $db = new PDO($protocol . ':host=' . $host . ';dbname=' . $database, $username, $password);
}
?>