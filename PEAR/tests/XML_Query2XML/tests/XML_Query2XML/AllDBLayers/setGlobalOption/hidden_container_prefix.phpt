--TEST--
XML_Query2XML::setGlobalOption(): setting the hidden_container_prefix
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $query2xml->setGlobalOption('hidden_container_prefix', 'SKIPME');
    $dom =& $query2xml->getXML(
        "SELECT
            *
         FROM
            artist
            LEFT JOIN album ON album.artist_id = artist.artistid
         ORDER BY
            artistid,
            albumid",
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
                    'rootTag' => 'SKIPME_albums',
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
    
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library>
  <artist>
    <artistid>1</artistid>
    <name>Curtis Mayfield</name>
    <birth_year>1920</birth_year>
    <birth_place>Chicago</birth_place>
    <genre>Soul</genre>
    <album>
      <albumid>1</albumid>
      <title>New World Order</title>
      <published_year>1990</published_year>
      <comment>the best ever!</comment>
    </album>
    <album>
      <albumid>2</albumid>
      <title>Curtis</title>
      <published_year>1970</published_year>
      <comment>that man's got somthin' to say</comment>
    </album>
  </artist>
  <artist>
    <artistid>2</artistid>
    <name>Isaac Hayes</name>
    <birth_year>1942</birth_year>
    <birth_place>Tennessee</birth_place>
    <genre>Soul</genre>
    <album>
      <albumid>3</albumid>
      <title>Shaft</title>
      <published_year>1972</published_year>
      <comment>he's the man</comment>
    </album>
  </artist>
  <artist>
    <artistid>3</artistid>
    <name>Ray Charles</name>
    <birth_year>1930</birth_year>
    <birth_place>Mississippi</birth_place>
    <genre>Country and Soul</genre>
  </artist>
</music_library>