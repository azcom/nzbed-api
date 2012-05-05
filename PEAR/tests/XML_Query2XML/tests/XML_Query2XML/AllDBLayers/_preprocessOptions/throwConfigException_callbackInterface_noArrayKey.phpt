--TEST--
XML_Query2XML::_preprocessOptions(): check for XML_Query2XML_ConfigException - command object without array key
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once 'XML/Query2XML/Callback.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    
    class MyCallback implements XML_Query2XML_Callback
    {
        public function execute(array $record)
        {
            return $record['name'];
        }
    }
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $query2xml->getXML(
            "SELECT
                *
             FROM
                artist",
            array(
                'rootTag' => 'music_library',
                'rowTag' => 'artist',
                'idColumn' => 'artistid',
                'attributes' => array(
                    new MyCallback()
                )
            )
        );
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: [attributes][0]: the element name has to be specified as the array key when the value is specified using an instance of XML_Query2XML_Callback.
