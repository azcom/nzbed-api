<?php
require_once 'XML/Query2XML.php';
require_once 'MDB2.php';
$query2xml = XML_Query2XML::factory(MDB2::factory('mysql://root@localhost/Query2XML_Tests'));

require_once 'Log.php';
$debugLogger = Log::factory('file', 'case02.log', 'Query2XML');
$query2xml->enableDebugLog($debugLogger);

$query2xml->startProfiling();


$dom = $query2xml->getXML(
    "SELECT
        *
     FROM
        artist
        LEFT JOIN album ON album.artist_id = artist.artistid
     ORDER BY
        artist.artistid,
        album.albumid",
    array(
        'rootTag' => 'music_library',
        'rowTag' => 'artist',
        'idColumn' => 'artistid',
        'elements' => array(
            'artistid',
            'name',
            'birth_year',
            'birth_place',
            'genre',
            'albums' => array(
                'rootTag' => 'albums',
                'rowTag' => 'album',
                'idColumn' => 'albumid',
                'elements' => array(
                    'albumid',
                    'title',
                    'published_year',
                    'comment'
                )
            )
        )
    )
);

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();

require_once 'File.php';
$fp = new File();
$fp->write('case02.profile', $query2xml->getProfile(), FILE_MODE_WRITE);
?>