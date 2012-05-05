--TEST--
XML_Query2XML::enableDebugLog() with $options['sql_options']['cached'] = true
--SKIPIF--
<?php $db_layers = array('MDB2', 'DB'); require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/db_init.php';
    
    class MyLogger
    {
        public $data = '';
        public function log($str)
        {
            $this->data .= $str . "\n";
        }
    }
    
    class MyDriver extends XML_Query2XML_Driver
    {
        public function __construct($driver, $logger)
        {
            $this->_driver = $driver;
            $this->_logger = $logger;
        }
        
        public function getAllRecords($sql, $configPath)
        {
            // this allows us to notice when the results are fetched from the DB
            if (strpos($sql['query'], '?') !== false) {
                $query = substr($sql['query'], 0, strpos($sql['query'], '?')+1);
            } else {
                $query = $sql['query'];
            }
            $this->_logger->log(
                'FROM DB: ' . $query
            );
            return $this->_driver->getAllRecords($sql, $configPath);
        }
        
        public function preprocessQuery(&$query, $configPath)
        {
            return $this->_driver->preprocessQuery($query, $configPath);
        }
    }

    $driver = XML_Query2XML_Driver::factory($db);
    $debugLogger = new MyLogger();
    $query2xml = XML_Query2XML::factory(new MyDriver($driver, $debugLogger));
    $query2xml->enableDebugLog($debugLogger);
    $dom =& $query2xml->getXML(
        'SELECT * FROM artist UNION ALL SELECT * FROM artist',
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
                        'query' => 'SELECT * FROM album WHERE artist_id = ?',
                        'limit' => 1
                    ),
                    'sql_options' => array(
                        'cached' => true
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
QUERY: SELECT * FROM artist UNION ALL SELECT * FROM artist
FROM DB: SELECT * FROM artist UNION ALL SELECT * FROM artist
DONE
QUERY: SELECT * FROM album WHERE artist_id = ?; LIMIT:1; OFFSET:0 (USING CACHING); DATA:1
FROM DB: SELECT * FROM album WHERE artist_id = ?
DONE
QUERY: SELECT * FROM album WHERE artist_id = ?; LIMIT:1; OFFSET:0 (USING CACHING); DATA:2
FROM DB: SELECT * FROM album WHERE artist_id = ?
DONE
QUERY: SELECT * FROM album WHERE artist_id = ?; LIMIT:1; OFFSET:0 (USING CACHING); DATA:3
FROM DB: SELECT * FROM album WHERE artist_id = ?
DONE
QUERY: SELECT * FROM album WHERE artist_id = ?; LIMIT:1; OFFSET:0 (USING CACHING); DATA:1
DONE
QUERY: SELECT * FROM album WHERE artist_id = ?; LIMIT:1; OFFSET:0 (USING CACHING); DATA:2
DONE
QUERY: SELECT * FROM album WHERE artist_id = ?; LIMIT:1; OFFSET:0 (USING CACHING); DATA:3
DONE
