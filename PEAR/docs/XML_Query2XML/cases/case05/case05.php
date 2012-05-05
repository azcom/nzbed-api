<?php
require_once 'XML/Query2XML.php';
require_once 'MDB2.php';
$query2xml = XML_Query2XML::factory(MDB2::factory('mysql://root@localhost/Query2XML_Tests'));
$dom = $query2xml->getXML(
    "SELECT
         *
     FROM
         customer c
         LEFT JOIN sale s ON c.customerid = s.customer_id
         LEFT JOIN album al ON s.album_id = al.albumid
         LEFT JOIN artist ar ON al.artist_id = ar.artistid
     ORDER BY
         c.customerid,
         s.saleid,
         al.albumid,
         ar.artistid",
    array(
        'rootTag' => 'music_store',
        'rowTag' => 'customer',
        'idColumn' => 'customerid',
        'elements' => array(
            'customerid',
            'first_name',
            'last_name',
            'email',
            'sales' => array(
                'rootTag' => 'sales',
                'rowTag' => 'sale',
                'idColumn' => 'saleid',
                'elements' => array(
                    'saleid',
                    'timestamp',
                    'date' => '#Callbacks::getFirstWord()',
                    'time' => '#Callbacks::getSecondWord()',
                    'album' => array(
                        'rootTag' => '',
                        'rowTag' => 'album',
                        'idColumn' => 'albumid',
                        'elements' => array(
                            'albumid',
                            'title',
                            'published_year',
                            'comment',
                            'artist' => array(
                                'rootTag' => '',
                                'rowTag' => 'artist',
                                'idColumn' => 'artistid',
                                'elements' => array(
                                    'artistid',
                                    'name',
                                    'birth_year',
                                    'birth_place',
                                    'genre'
                                ) //artist elements
                            ) //artist array
                        ) //album elements
                    ) //album array
                ) //sales elements
            ) //sales array
        ) //root elements
    ) //root
); //getXML method call

$root = $dom->firstChild;
$root->setAttribute('date_generated', '2005-08-23T14:52:50');

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();

class Callbacks
{
    function getFirstWord($record)
    {
        return substr($record['timestamp'], 0, strpos($record['timestamp'], ' '));
    }
    
    function getSecondWord($record)
    {
        return substr($record['timestamp'], strpos($record['timestamp'], ' ') + 1);
    }
}
?>