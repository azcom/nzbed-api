<?php

require_once 'PEAR.php';
require_once 'HTTP/Download.php';

PEAR::setErrorHandling(PEAR_ERROR_PRINT);

$params = @$_GET['params'];

// Whatch for hackers
unset($params['file']);
unset($params['resource']);
unset($params['stream']);
unset($params['data']);

// Stream to test unknown-length content
class testStream
{
    var $_fp;
    function stream_open($path, $mode, $flags, &$opened)
    {
        $path = substr($path, 9);
        $this->_fp = fopen($path, $mode, true);
        return (boolean)$this->_fp;
    }

    function stream_close()
    {
        fclose($this->_fp);
        return true;
    }

    function stream_eof()
    {
        return feof($this->_fp);
    }

    function stream_read($count)
    {
        return fread($this->_fp, $count);
    }

    function stream_seek($offset, $whence)
    {
        return fseek($this->_fp, $offset, $whence);
    }

    function stream_stat()
    {
        return array();
    }
}

stream_wrapper_register('mytest', 'testStream');


switch ($_GET['what'])
{
    case 'file':
        $params['file'] = 'data.txt';
    break;
    case 'resource':
        $params['resource'] = fopen('data.txt', 'rb');
    break;
    case 'stream':
        $params['resource'] = fopen('mytest://data.txt', 'rb');
    break;
    case 'data':
        $params['data'] = file_get_contents('data.txt');
    break;
}

switch ($_GET['op'])
{
    case 'static':
        HTTP_Download::staticSend($params);
    break;

    case 'send':
        $h = &new HTTP_Download;
        $h->setParams($params);
        $h->send();
    break;

    case 'arch':
        HTTP_Download::sendArchive('foo.'. $_GET['type'], $_GET['what'], $_GET['type']);
    break;
}

?>
