--TEST--
XML_Query2XML::getXML(): unserialization prefix: no container (scenarios 3,4,7,8,9,10)
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
/*
    What will get added using different scenarios
    1. 'xmldata' => '&xml_data_column'
        if xml_data_column is NULL or '': <xmldata/>
        if xml_data_column contains '<p/>':  <xmldata><p/></xmldata>
        Note: like 5
    2. 'xmldata' => '?&xml_data_column'
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <xmldata><p/></xmldata>
        Note: like 6
    3. '__xmldata' => '&xml_data_column'
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <p/>
        Note: like 4,7,8,9,10
    4. '__xmldata' => '?&xml_data_column'
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <p/>
        Note: like 3,7,8,9,10
    5. 'xmldata' => array(
        'value' => '&xml_data_column'
    )
        if xml_data_column is NULL or '': <xmldata/>
        if xml_data_column contains '<p/>':  <xmldata><p/></xmldata>
        Note: like 1
    6. 'xmldata' => array(
        'value' => '?&xml_data_column'
    )
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <xmldata><p/></xmldata>
        Note: 2.
    7. '__xmldata' => array(
        'value' => '&xml_data_column'
    )
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <p/>
        Note: like 3,4,8
    8. '__xmldata' => array(
        'value' => '?&xml_data_column'
    )
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <p/>
        Note: like 3,4,7
    9. 'xmldata' => array(
        'rowTag' => '__row',
        'value' => '&xml_data_column'
    )
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <p/>
        Note: like 3,4,7,8,10
    10. 'xmldata' => array(
        'rowTag' => '__row',
        'value' => '?&xml_data_column'
    )
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <p/>
        Note: like 3,4,7,8,9,10
    
    Different results are possible:
      Container always present
        if xml_data_column is NULL or '': <xmldata/>
        if xml_data_column contains '<p/>':  <xmldata><p/></xmldata>
        --> scenarios 1,5
      
      Container only present if children
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <xmldata><p/></xmldata>
        --> scenarios 2,6
      
      No Container
        if xml_data_column is NULL or '': nothing
        if xml_data_column contains '<p/>':  <p/>
        --> scenarios 3,4,7,8,9,10
*/
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $dom =& $query2xml->getXML(
        "SELECT
            *,
            '<p/>' AS xml_data_column,
            NULL   AS xml_data_column_null,
            ''     AS xml_data_column_empty
         FROM
            store WHERE storeid = 1
         ORDER BY
            storeid",
        array(
            'rootTag' => 'music_stores',
            'rowTag' => 'store',
            'idColumn' => 'storeid',
            'elements' => array(
                'storeid',
                'country',
                'state',
                'city',
                'street',
                'phone',
                //xml_data_column is '<p/>'
                '__xml_data_column' => '&xml_data_column',   //scenario 3
                '__xml_data_column4' => '?&xml_data_column', //scenario 4
                '__xml_data_column7' => array(               //scenario 7
                    'value' => '&xml_data_column'
                ),
                '__xml_data_column10' => array(              //scenario 8
                    'value' => '?&xml_data_column'
                ),
                '__xml_data_column13' => array(              //scenario 9
                    'rowTag' => '__row',
                    'value' => '&xml_data_column'
                ),
                '__xml_data_column16' => array(              //scenario 10
                    'rowTag' => '__row',
                    'value' => '?&xml_data_column'
                ),
                
                
                //xml_data_column is NULL
                '__xml_data_column2' => '&xml_data_column_null',  //scenario 3
                '__xml_data_column5' => '?&xml_data_column_null', //scenario 4
                '__xml_data_column8' => array(                    //scenario 7
                    'value' => '&xml_data_column_null'
                ),
                '__xml_data_column11' => array(                   //scenario 8
                    'value' => '?&xml_data_column_null'
                ),
                '__xml_data_column14' => array(                   //scenario 9
                    'rowTag' => '__row',
                    'value' => '&xml_data_column_null'
                ),
                '__xml_data_column17' => array(                   //scenario 10
                    'rowTag' => '__row',
                    'value' => '?&xml_data_column_null'
                ),
                
                
                //xml_data_column is '' (empty)
                '__xml_data_column3' => '&xml_data_column_empty',  //scenario 3
                '__xml_data_column6' => '?&xml_data_column_empty', //scenario 4
                '__xml_data_column9' => array(                     //scenario 7
                    'value' => '&xml_data_column_empty'
                ),
                '__xml_data_column12' => array(                   //scenario 8
                    'value' => '?&xml_data_column_empty'
                ),
                '__xml_data_column15' => array(                   //scenario 9
                    'rowTag' => '__row',
                    'value' => '&xml_data_column_empty'
                ),
                '__xml_data_column18' => array(                   //scenario 10
                    'rowTag' => '__row',
                    'value' => '?&xml_data_column_empty'
                ),
            )
        )
    );
    $dom->formatOutput = true;
    print $dom->saveXML();
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_stores>
  <store>
    <storeid>1</storeid>
    <country>US</country>
    <state>New York</state>
    <city>New York</city>
    <street>Broadway &amp; 72nd Str</street>
    <phone>123 456 7890</phone>
    <p/>
    <p/>
    <p/>
    <p/>
    <p/>
    <p/>
  </store>
</music_stores>