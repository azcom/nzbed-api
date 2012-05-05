--TEST--
XML_Query2XML::enableDebugLog() with two placeholders
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    class MyLogger
    {
        public $data = '';
        public function log($str)
        {
            $this->data .= $str . "\n";
        }
    }

    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    $query2xml =& XML_Query2XML::factory($db);
    $debugLogger = new MyLogger();
    $query2xml->enableDebugLog($debugLogger);
    $dom =& $query2xml->getXML(
        "SELECT
            *
         FROM
            artist",
        array(
            'rootTag' => 'music_library',
            'rowTag' => 'artist',
            'idColumn' => 'artistid',
            'elements' => array(
                'artistid',
                'name',
                'birth_year',
                'birth_place',
                'genre',
                'albums' => array(
                    'sql' => array(
                        'data' => array(
                            'artistid',
                            ':1'
                        ),
                        'query' => 'SELECT * FROM album WHERE artist_id = ? AND 1=?'
                    ),
                    'rootTag' => 'albums',
                    'rowTag' => 'album',
                    'idColumn' => 'albumid',
                    'elements' => array(
                        'albumid',
                        'title',
                        'published_year',
                        'comment'
                    )
                )
            )
        )
    );
    $query2xml->disableDebugLog();
    echo $debugLogger->data;
?>
--EXPECT--
QUERY: SELECT
            *
         FROM
            artist
DONE
QUERY: SELECT * FROM album WHERE artist_id = ? AND 1=?; DATA:1,1
DONE
QUERY: SELECT * FROM album WHERE artist_id = ? AND 1=?; DATA:2,1
DONE
QUERY: SELECT * FROM album WHERE artist_id = ? AND 1=?; DATA:3,1
DONE

