--TEST--
XML_Query2XML::getXML(): $options['sql_options']['single_record'] = true
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom = $query2xml->getXML(
        "SELECT * FROM artist",
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'artistid',
                'name',
                'album' => array(
                    'idColumn' => 'albumid',
                    'sql' => array(
                        'data' => array(
                            'artistid'
                        ),
                        'query' => 'SELECT * FROM album WHERE album.artist_id = ?'
                    ),
                    'sql_options' => array(
                        'single_record' => true,
                        'cached' => true
                    ),
                    'elements' => array(
                        'albumid',
                        'title'
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
    <album>
      <albumid>1</albumid>
      <title>New World Order</title>
    </album>
  </artist>
  <artist>
    <artistid>2</artistid>
    <name>Isaac Hayes</name>
    <album>
      <albumid>3</albumid>
      <title>Shaft</title>
    </album>
  </artist>
  <artist>
    <artistid>3</artistid>
    <name>Ray Charles</name>
  </artist>
</music_library>
