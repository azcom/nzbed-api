--TEST--
XML_Query2XML::_processComplexElementSpecification(): check for XML_Query2XML_ConfigException - missing "value" option
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
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
                    '*' => '*',
                    'genre' => array(
                        'condition' => '#genreEqualsSoul()',
                    )
                )
            )
        );
    } catch (XML_Query2XML_ConfigException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    }
    
    function genreEqualsSoul($record)
    {
        return $record["genre"] == "Soul";
    }
?>
--EXPECT--
XML_Query2XML_ConfigException: [attributes][genre][value]: Mandatory option "value" missing from the complex attribute specification.
