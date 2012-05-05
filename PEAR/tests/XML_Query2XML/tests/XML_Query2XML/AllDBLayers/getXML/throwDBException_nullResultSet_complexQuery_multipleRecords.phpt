--TEST--
XML_Query2XML::getXML(): check for XML_Query2XML_DBException - complex query specification returns null result set
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    $exceptionThrown = false;
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    try {
        $query2xml =& XML_Query2XML::factory($db);
        $query2xml->getXML(
            "SELECT * FROM artist",
            array(
                'rootTag' => 'music_library',
                'rowTag' => 'artist',
                'idColumn' => 'artistid',
                'elements' => array(
                    'artistid',
                    'name',
                    'album' => array(
                        'idColumn' => 'albumid',
                        'sql' => array(
                            'query' => 'UPDATE album SET title="" WHERE album.artist_id = 1 AND 1=2'
                        ),
                        'elements' => array(
                            'albumid',
                            'title'
                        )
                    )
                )
            )
        );
    } catch (XML_Query2XML_DBException $e) {
        echo get_class($e) . ': ' . substr($e->getMessage(), 0, 24);
        $exceptionThrown = true;
    }
    /*
    * PDO: PDO's mysql driver will return a valid PDOStatement from PDO::execute()
    *      that allows PDOStatement::fetchRow() to be called without an exception
    *      being thrown.
    *
    * DB: will return DB_OK instead of DB_Result for DDL statements
    *
    * MDB: will return an empty result set when sqlite or mysql is used, but
    *      a PEAR_Error if pgsql used
    *
    * This unit tests therefore effectivly checks that the database abstraction layers
    * either throw an XML_Query2XML_DBException or act as if the result set was empty.
    */
    if (!$exceptionThrown) {
        echo "XML_Query2XML_DBException: [elements][album][sql]: ";
    }
?>
--EXPECT--
XML_Query2XML_DBException: [elements][album][sql]: 