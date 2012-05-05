<?php
require_once 'XML/Query2XML.php';
require_once 'MDB2.php';
$query2xml = XML_Query2XML::factory(MDB2::factory('mysql://root@localhost/Query2XML_Tests'));
$dom = $query2xml->getFlatXML(
    "SELECT
        *
     FROM
        artist
     ORDER BY
        artistid",
    'music_library',
    'artist');

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();
?>