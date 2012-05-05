--TEST--
XML_Query2XML::getFlatXML(): simple select
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getFlatXML('SELECT * FROM album ORDER BY albumid');
    echo $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<root><row><albumid>1</albumid><artist_id>1</artist_id><title>New World Order</title><published_year>1990</published_year><comment>the best ever!</comment></row><row><albumid>2</albumid><artist_id>1</artist_id><title>Curtis</title><published_year>1970</published_year><comment>that man's got somthin' to say</comment></row><row><albumid>3</albumid><artist_id>2</artist_id><title>Shaft</title><published_year>1972</published_year><comment>he's the man</comment></row></root>
