--TEST--
XML_Query2XML::getXML(): using the callback interface for a complex attribute specification
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once 'XML/Query2XML/Callback.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    
    class MyCallback implements XML_Query2XML_Callback
    {
        private $_columnName = '';
        
        public function __construct($columnName)
        {
            $this->_columnName = $columnName;
        }
        
        public function execute(array $record)
        {
            return $record[$this->_columnName];
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
            'attributes' => array(
                'albumid',
                'title' => array(
                    'value' => new MyCallback('title')
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
  <album albumid="1" title="New World Order"/>
  <album albumid="2" title="Curtis"/>
  <album albumid="3" title="Shaft"/>
</music_store>
