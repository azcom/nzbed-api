<?php
require_once 'XML/Query2XML.php';
require_once 'MDB2.php';
$query2xml = XML_Query2XML::factory(MDB2::factory('mysql://root@localhost/Query2XML_Tests'));

require_once 'Log.php';
$debugLogger = Log::factory('file', 'case04.log', 'Query2XML');
$query2xml->enableDebugLog($debugLogger);

$query2xml->startProfiling();


$dom = $query2xml->getXML(
    "SELECT
        *
     FROM
        artist
     ORDER BY
        artistid",
    array(
        'rootTag' => 'MUSIC_LIBRARY',
        'rowTag' => 'ARTIST',
        'idColumn' => 'artistid',
        'elements' => array(
            'NAME' => 'name',
            'BIRTH_YEAR' => 'birth_year',
            'BIRTH_YEAR_TWO_DIGIT' => "#firstTwoChars()",
            'BIRTH_PLACE' => 'birth_place',
            'GENRE' => 'genre',
            'albums' => array(
                'sql' => array(
                    'data' => array(
                        'artistid'
                    ),
                    'query' => 'SELECT * FROM album WHERE artist_id = ?'
                ),
                'sql_options' => array(
                    'merge_selective' => array('genre')
                ),
                'rootTag' => '',
                'rowTag' => 'ALBUM',
                'idColumn' => 'albumid',
                'elements' => array(
                    'TITLE' => 'title',
                    'PUBLISHED_YEAR' => 'published_year',
                    'COMMENT' => 'comment',
                    'GENRE' => 'genre'
                ),
                'attributes' => array(
                    'ALBUMID' => 'albumid'
                )
            )
        ),
        'attributes' => array(
            'ARTISTID' => 'artistid',
            'MAINTAINER' => ':Lukas Feiler'
        )
    )
);

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();

require_once 'File.php';
$fp = new File();
$fp->write('case04.profile', $query2xml->getProfile(), FILE_MODE_WRITE);

function firstTwoChars($record)
{
    return substr($record['birth_year'], 2);
}
?>