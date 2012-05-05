--TEST--
XML_Query2XML::_applyColumnStringToRecord(): check for XML_Query2XML_ConfigException - non-existing column used as idColumn
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    
    $query2xml =& XML_Query2XML::factory($db);
    $dom = $query2xml->getXML(
        "SELECT
            *
         FROM
            artist
         ORDER BY
            artistid",
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'artistid',
                'name' => '#uppercaseName',
                'name2' => '#uppercaseName()',
                'name3' => '#uppercase(name)',
                'name4' => '#prefixNameWithUnderscore',
                'name5' => '#prefixNameWithUnderscore()',
                'name6' => '#prefixWithUnderscore(name)',
                'name7' => '#prefixNameWith(_)',
                'name8' => '#prefixWith(name, _)',
                'name9' => '#Callbacks::prefixNameWithUnderscore',
                'name10' => '#Callbacks::prefixNameWithUnderscore()',
                'name11' => '#Callbacks::prefixWithUnderscore(name)',
                'name12' => '#Callbacks::prefixNameWith(_)',
                'name13' => '#Callbacks::prefixWith(name, _)',
            )
        )
    );
    print $dom->saveXML();
    
    function uppercaseName($record)
    {
        return strtoupper($record['name']);
    }
    
    function uppercase($record, $columnName)
    {
        return strtoupper($record[$columnName]);
    }
    
    function prefixNameWithUnderscore($record)
    {
        return '_' . $record['name'];
    }
    
    function prefixWithUnderscore($record, $columnName)
    {
        return '_' . $record[$columnName];
    }
    
    function prefixNameWith($record, $with)
    {
        return $with . $record['name'];
    }
    
    function prefixWith($record, $columnName, $with)
    {
        return $with . $record[$columnName];
    }
    
    class Callbacks
    {
            function prefixNameWithUnderscore($record)
            {
                return '_' . $record['name'];
            }
            
            function prefixWithUnderscore($record, $columnName)
            {
                return '_' . $record[$columnName];
            }
            
            function prefixNameWith($record, $with)
            {
                return $with . $record['name'];
            }
            
            function prefixWith($record, $columnName, $with)
            {
                return $with . $record[$columnName];
            }
    }
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_library><artist><artistid>1</artistid><name>CURTIS MAYFIELD</name><name2>CURTIS MAYFIELD</name2><name3>CURTIS MAYFIELD</name3><name4>_Curtis Mayfield</name4><name5>_Curtis Mayfield</name5><name6>_Curtis Mayfield</name6><name7>_Curtis Mayfield</name7><name8>_Curtis Mayfield</name8><name9>_Curtis Mayfield</name9><name10>_Curtis Mayfield</name10><name11>_Curtis Mayfield</name11><name12>_Curtis Mayfield</name12><name13>_Curtis Mayfield</name13></artist><artist><artistid>2</artistid><name>ISAAC HAYES</name><name2>ISAAC HAYES</name2><name3>ISAAC HAYES</name3><name4>_Isaac Hayes</name4><name5>_Isaac Hayes</name5><name6>_Isaac Hayes</name6><name7>_Isaac Hayes</name7><name8>_Isaac Hayes</name8><name9>_Isaac Hayes</name9><name10>_Isaac Hayes</name10><name11>_Isaac Hayes</name11><name12>_Isaac Hayes</name12><name13>_Isaac Hayes</name13></artist><artist><artistid>3</artistid><name>RAY CHARLES</name><name2>RAY CHARLES</name2><name3>RAY CHARLES</name3><name4>_Ray Charles</name4><name5>_Ray Charles</name5><name6>_Ray Charles</name6><name7>_Ray Charles</name7><name8>_Ray Charles</name8><name9>_Ray Charles</name9><name10>_Ray Charles</name10><name11>_Ray Charles</name11><name12>_Ray Charles</name12><name13>_Ray Charles</name13></artist></music_library>
