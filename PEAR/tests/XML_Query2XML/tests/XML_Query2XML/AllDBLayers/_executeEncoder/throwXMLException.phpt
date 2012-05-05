--TEST--
XML_Query2XML::_createDOMElement(): check for XML_Query2XML_XMLException - encoder throwing exception
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    function myEncoder($str)
    {   
        throw new Exception('some error');
        return utf8_encode($str);
    }
    
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    try {
        $dom =& $query2xml->getXML(
            "SELECT
                artistid,
                'Père Noël' AS name
             FROM
                artist",
            array(
                'rootTag' => 'xmas',
                'rowTag' => 'CEO',
                'encoder' => 'myEncoder',
                'idColumn' => 'artistid',
                'elements' => array(
                    'name'
                )
            )
        );
    } catch (XML_Query2XML_XMLException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
?>
--EXPECT--
XML_Query2XML_XMLException: [encoder]: Could not encode "Père Noël": some error
