--TEST--
XML_Query2XML::getXML(): using the callback interface for an idColumn specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once 'XML/Query2XML/Callback.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    class Test{}
    class MyCallback implements XML_Query2XML_Callback
    {
        private $_columnName = '';
        
        public function __construct($columnName)
        {
            $this->_columnName = $columnName;
        }
        
        public function execute(array $record)
        {
            return $record[$this->_columnName] != '';
        }
    }
    
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
                'title' => array(
                    'value' => 'title',
                    'condition' => new MyCallback('title')
                )
            )
        )
    );
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_store>
  <album>
    <albumid>1</albumid>
    <title>New World Order</title>
  </album>
  <album>
    <albumid>2</albumid>
    <title>Curtis</title>
  </album>
  <album>
    <albumid>3</albumid>
    <title>Shaft</title>
  </album>
</music_store>
