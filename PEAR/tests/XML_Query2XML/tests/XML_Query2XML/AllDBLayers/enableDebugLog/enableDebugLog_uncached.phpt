--TEST--
XML_Query2XML::enableDebugLog() with $options['sql_options']['uncached'] = false (deprecated)
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
                            'artistid'
                        ),
                        'query' => 'SELECT * FROM album WHERE artist_id = ?'
                    ),
                    'sql_options' => array(
                        'uncached' => false
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
QUERY: SELECT * FROM album WHERE artist_id = ? (USING CACHING); DATA:1
DONE
QUERY: SELECT * FROM album WHERE artist_id = ? (USING CACHING); DATA:2
DONE
QUERY: SELECT * FROM album WHERE artist_id = ? (USING CACHING); DATA:3
DONE