--TEST--
XML_Query2XML::getXML(): Case04
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
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

function firstTwoChars($record)
{
    return substr($record['birth_year'], 2);
}
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<MUSIC_LIBRARY>
  <ARTIST ARTISTID="1" MAINTAINER="Lukas Feiler">
    <NAME>Curtis Mayfield</NAME>
    <BIRTH_YEAR>1920</BIRTH_YEAR>
    <BIRTH_YEAR_TWO_DIGIT>20</BIRTH_YEAR_TWO_DIGIT>
    <BIRTH_PLACE>Chicago</BIRTH_PLACE>
    <GENRE>Soul</GENRE>
    <ALBUM ALBUMID="1">
      <TITLE>New World Order</TITLE>
      <PUBLISHED_YEAR>1990</PUBLISHED_YEAR>
      <COMMENT>the best ever!</COMMENT>
      <GENRE>Soul</GENRE>
    </ALBUM>
    <ALBUM ALBUMID="2">
      <TITLE>Curtis</TITLE>
      <PUBLISHED_YEAR>1970</PUBLISHED_YEAR>
      <COMMENT>that man's got somthin' to say</COMMENT>
      <GENRE>Soul</GENRE>
    </ALBUM>
  </ARTIST>
  <ARTIST ARTISTID="2" MAINTAINER="Lukas Feiler">
    <NAME>Isaac Hayes</NAME>
    <BIRTH_YEAR>1942</BIRTH_YEAR>
    <BIRTH_YEAR_TWO_DIGIT>42</BIRTH_YEAR_TWO_DIGIT>
    <BIRTH_PLACE>Tennessee</BIRTH_PLACE>
    <GENRE>Soul</GENRE>
    <ALBUM ALBUMID="3">
      <TITLE>Shaft</TITLE>
      <PUBLISHED_YEAR>1972</PUBLISHED_YEAR>
      <COMMENT>he's the man</COMMENT>
      <GENRE>Soul</GENRE>
    </ALBUM>
  </ARTIST>
  <ARTIST ARTISTID="3" MAINTAINER="Lukas Feiler">
    <NAME>Ray Charles</NAME>
    <BIRTH_YEAR>1930</BIRTH_YEAR>
    <BIRTH_YEAR_TWO_DIGIT>30</BIRTH_YEAR_TWO_DIGIT>
    <BIRTH_PLACE>Mississippi</BIRTH_PLACE>
    <GENRE>Country and Soul</GENRE>
  </ARTIST>
</MUSIC_LIBRARY>
