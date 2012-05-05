<?php

define( MYSQLINC_INCLUDE, 1 );

if (!defined(MYSQL_INCLUDE)) { require_once( INCLUDEPATH .'mysql.php' ); }

$options['hostname'] = 'hostname'; // Hostname
$options['username'] = 'username'; // Username
$options['password'] = 'password'; // Password
$options['dbname'] = 'dbname'; // Database name

$db = new mysql( $options );
$db->connect();
?>