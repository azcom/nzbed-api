--TEST--
XML_Query2XML::getXML(): returning an array of DOMNode instances from a callback with the parent element being hidden
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
                '__time' => '#getTime()'
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
        
        $rfc2822date = $dom->createElement('rfc2822date');
        $rfc2822date->appendChild($dom->createTextNode('Thu, 01 Feb 2007 19:32:12 +0100'));
        return array($unixtime, $rfc2822date);
    }
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_store>
  <album>
    <albumid>1</albumid>
    <title>New World Order</title>
    <unixtime>1170354732</unixtime>
    <rfc2822date>Thu, 01 Feb 2007 19:32:12 +0100</rfc2822date>
  </album>
  <album>
    <albumid>2</albumid>
    <title>Curtis</title>
    <unixtime>1170354732</unixtime>
    <rfc2822date>Thu, 01 Feb 2007 19:32:12 +0100</rfc2822date>
  </album>
  <album>
    <albumid>3</albumid>
    <title>Shaft</title>
    <unixtime>1170354732</unixtime>
    <rfc2822date>Thu, 01 Feb 2007 19:32:12 +0100</rfc2822date>
  </album>
</music_store>
