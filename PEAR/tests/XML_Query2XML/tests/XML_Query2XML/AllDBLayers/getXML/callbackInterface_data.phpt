--TEST--
XML_Query2XML::getXML(): using the callback interface for an idColumn specification
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
            artist",
        array(
            'rootTag' => 'music_store',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'artistid',
                'name',
                'albums' => array(
                    'idColumn' => 'albumid',
                    'sql' => array(
                        'data' => array(
                            new MyCallback('artistid')
                        ),
                        'query' => 'SELECT * FROM album WHERE artist_id = ?'
                    ),
                    'elements' => array(
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
<music_store>
  <artist>
    <artistid>1</artistid>
    <name>Curtis Mayfield</name>
    <albums>
      <title>New World Order</title>
    </albums>
    <albums>
      <title>Curtis</title>
    </albums>
  </artist>
  <artist>
    <artistid>2</artistid>
    <name>Isaac Hayes</name>
    <albums>
      <title>Shaft</title>
    </albums>
  </artist>
  <artist>
    <artistid>3</artistid>
    <name>Ray Charles</name>
  </artist>
</music_store>
