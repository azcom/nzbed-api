--TEST--
XML_Query2XML::getXML(): returning a DOMNode instance from a callback
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml = XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            *
         FROM
            album",
        array(
            'rootTag' => 'music_store',
            'rowTag' => 'album',
            'idColumn' => 'albumid',
            'elements' => array(
                'albumid',
                'title',
                'time' => '#getTime()'
            )
        )
    );
    $dom->formatOutput = true;
    print $dom->saveXML();
    
    function getTime()
    {
        $dom = new DOMDocument();
        $unixtime = $dom->createElement('unixtime');
        $unixtime->appendChild($dom->createTextNode(1170354732));
        return $unixtime;
    }
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_store>
  <album>
    <albumid>1</albumid>
    <title>New World Order</title>
    <time>
      <unixtime>1170354732</unixtime>
    </time>
  </album>
  <album>
    <albumid>2</albumid>
    <title>Curtis</title>
    <time>
      <unixtime>1170354732</unixtime>
    </time>
  </album>
  <album>
    <albumid>3</albumid>
    <title>Shaft</title>
    <time>
      <unixtime>1170354732</unixtime>
    </time>
  </album>
</music_store>
