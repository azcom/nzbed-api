<?php

require_once 'PHPUnit/Framework.php';
require_once 'HTTP/Download.php';
require_once 'HTTP/Request.php';

class HTTP_DownloadTest extends PHPUnit_Framework_TestCase {

    function setUp()
    {
        $this->testScript = 'http://local/www/mike/pear/HTTP_Download/tests/send.php';
    }

    function tearDown()
    {
    }

    function testHTTP_Download()
    {
        $this->assertTrue(is_a($h = &new HTTP_Download, 'HTTP_Download'));
        $this->assertTrue(is_a($h->HTTP, 'HTTP_Header'));
        unset($h);
    }

    function testsetFile()
    {
        $h = &new HTTP_Download;
        $this->assertFalse(PEAR::isError($h->setFile(dirname(__FILE__) . '/data.txt')), '$h->setFile("data.txt")');
        $this->assertEquals(realpath(dirname(__FILE__) . '/data.txt'), $h->file, '$h->file == "data.txt');
        $this->assertTrue(PEAR::isError($h->setFile('nonexistant', false)), '$h->setFile("nonexistant")');
        unset($h);
    }

    function testsetData()
    {
        $h = &new HTTP_Download;
        $this->assertTrue(null === $h->setData('foo'), 'null === $h->setData("foo")');
        $this->assertEquals('foo', $h->data, '$h->data == "foo"');
        unset($h);
    }

    function testsetResource()
    {
        $h = &new HTTP_Download;
        $this->assertFalse(PEAR::isError($h->setResource($f = fopen(dirname(__FILE__) . '/data.txt', 'r'))), '$h->setResource(fopen("data.txt","r"))');
        $this->assertEquals($f, $h->handle, '$h->handle == $f');
        fclose($f); $f = -1;
        $this->assertTrue(PEAR::isError($h->setResource($f)), '$h->setResource($f = -1)');
        unset($h);
    }

    function testsetGzip()
    {
        $h = &new HTTP_Download;
        $this->assertFalse(PEAR::isError($h->setGzip(false)), '$h->setGzip(false)');
        $this->assertFalse($h->gzip, '$h->gzip');
        if (PEAR::loadExtension('zlib')) {
            $this->assertFalse(PEAR::isError($h->setGzip(true)), '$h->setGzip(true) without ext/zlib');
            $this->assertTrue($h->gzip, '$h->gzip');
        } else {
            $this->assertTrue(PEAR::isError($h->setGzip(true)), '$h->setGzip(true) with ext/zlib');
            $this->assertFalse($h->gzip, '$h->gzip');
        }
        unset($h);
    }

    function testsetContentType()
    {
        $h = &new HTTP_Download;
        $this->assertFalse(PEAR::isError($h->setContentType('text/html;charset=iso-8859-1')), '$h->setContentType("text/html;charset=iso-8859-1")');
        $this->assertTrue(PEAR::isError($h->setContentType('##++***!§§§§?°°^^}][{')), '$h->setContentType("some weird characters")');
        $this->assertEquals('text/html;charset=iso-8859-1', $h->headers['Content-Type'], '$h->headers["Content-Type"] == "text/html;charset=iso-8859-1"');
        unset($h);
    }

    function testguessContentType()
    {
        $h = &new HTTP_Download(array('file' => dirname(__FILE__) . '/data.txt'));
        $e = $h->guessContentType();
        if (PEAR::isError($e) && $e->getCode() != HTTP_DOWNLOAD_E_NO_EXT_MMAGIC) {
            $this->assertTrue(false, $e->getMessage());
        }
        unset($h, $e);
    }

    function _send($op)
    {
        if (!file_get_contents($this->testScript)) {
            $this->markTestSkipped($this->testScript . " is not available");
        }
        $complete = str_repeat('1234567890',10);
       
        $r = &new HTTP_Request($this->testScript);
        foreach (array('file', 'resource', 'data') as $what) {
            $r->reset($this->testScript);

            // without range
            $r->addQueryString('op', $op);
            $r->addQueryString('what', $what);
            $r->addQueryString('buffersize', 33);
            $r->sendRequest();
            $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
            $this->assertEquals($complete, $r->getResponseBody(), $what);

            // range 1-5
            $r->addHeader('Range', 'bytes=1-5');
            $r->sendRequest();
            $this->assertEquals(206, $r->getResponseCode(), 'HTTP 206 Partial Content');
            $this->assertEquals('23456', $r->getResponseBody(), $what);

            // range -5
            $r->addHeader('Range', 'bytes=-5');
            $r->sendRequest();
            $this->assertEquals(206, $r->getResponseCode(), 'HTTP 206 Partial Content');
            $this->assertEquals('67890', $r->getResponseBody(), $what);

            // range 95-
            $r->addHeader('Range', 'bytes=95-');
            $r->sendRequest();
            $this->assertEquals(206, $r->getResponseCode(), 'HTTP 206 Partial Content');
            $this->assertEquals('67890', $r->getResponseBody(), $what);
            $this->assertTrue(preg_match('/^bytes 95-\d+/', $r->getResponseHeader('content-range')), 'bytes keyword in Content-Range header');

            // multiple non-overlapping ranges
            $r->addHeader('Range', 'bytes=2-23,45-51, 24-44');
            $r->sendRequest();
            $this->assertEquals(206, $r->getResponseCode(), 'HTTP 206 Partial Content');
            $this->assertTrue(preg_match('/^multipart\/byteranges; boundary=HTTP_DOWNLOAD-[a-f0-9.]{23}$/', $r->getResponseHeader('content-type')), 'Content-Type header: multipart/byteranges');
            $this->assertTrue(preg_match('/Content-Range: bytes 2-23/', $r->getResponseBody()), 'bytes keyword in Content-Range header');

            // multiple overlapping ranges
            $r->addHeader('Range', 'bytes=2-23,45-51,22-46');
            $r->sendRequest();
            $this->assertEquals(206, $r->getResponseCode(), 'HTTP 206 Partial Content');
            $this->assertEquals('bytes 2-51/100', $r->getResponseHeader('content-range'), 'bytes keyword in Content-Range header');

            // Invalid range #1 (54-51)
            $r->addHeader('Range', 'bytes=2-23,54-51,22-46');
            $r->sendRequest();
            $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
            $this->assertEquals('100', $r->getResponseHeader('content-length'), 'full content');
            $this->assertEquals($complete, $r->getResponseBody(), $what);

            // Invalid range #2 (maformed range)
            $r->addHeader('Range', 'bytes=2-23 24-');
            $r->sendRequest();
            $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
            $this->assertEquals('100', $r->getResponseHeader('content-length'), 'full content');
            $this->assertEquals($complete, $r->getResponseBody(), $what);

            // Invalid range #3 (451-510)
            $r->addHeader('Range', 'bytes=451-510, -0');
            $r->sendRequest();
            $this->assertEquals(416, $r->getResponseCode(), 'HTTP 416 Unsatisfiable range');
        }

        // Stream
        $what = 'stream';
        $r->reset($this->testScript);

        // without range
        $r->addQueryString('op', $op);
        $r->addQueryString('what', $what);
        $r->addQueryString('buffersize', 33);
        $r->sendRequest();
        $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
        $this->assertEquals($complete, $r->getResponseBody(), $what);
        $this->assertFalse($r->getResponseHeader('content-range'), 'No range');

        // range 1-5
        $r->addHeader('Range', 'bytes=1-5');
        $r->sendRequest();
        $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
        $this->assertEquals($complete, $r->getResponseBody(), $what);
        $this->assertFalse($r->getResponseHeader('content-length'), 'Length unknown');
        $this->assertFalse($r->getResponseHeader('content-range'), 'No range');

        // range -5
        $r->addHeader('Range', 'bytes=-5');
        $r->sendRequest();
        $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
        $this->assertEquals($complete, $r->getResponseBody(), $what);
        $this->assertFalse($r->getResponseHeader('content-length'), 'Length unknown');
        $this->assertFalse($r->getResponseHeader('content-range'), 'No range');

        // range 95-
        $r->addHeader('Range', 'bytes=95-');
        $r->sendRequest();
        $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
        $this->assertEquals($complete, $r->getResponseBody(), $what);
        $this->assertFalse($r->getResponseHeader('content-length'), 'Length unknown');
        $this->assertFalse($r->getResponseHeader('content-range'), 'No range');

        unset($r);
    }

    function testsend()
    {
        $this->_send('send');
    }

    function teststaticSend()
    {
        $this->_send('static');
    }

    function testsendArchive()
    {
        if (!file_get_contents($this->testScript)) {
            $this->markTestSkipped($this->testScript . " is not available");
        }

        $r = &new HTTP_Request($this->testScript);
        foreach (array('tar', 'tgz', 'zip', 'bz2') as $type) {
            $r->addQueryString('type', $type);
            $r->addQueryString('op', 'arch');

            $r->addQueryString('what', 'data.txt');
            $r->sendRequest();
            $this->assertEquals(200, $r->getResponseCode(), 'HTTP 200 Ok');
            $this->assertTrue($r->getResponseHeader('content-length') > 0, 'Content-Length > 0');
            $this->assertTrue(preg_match('/application\/x-(tar|gzip|bzip2|zip)/', $t = $r->getResponseHeader('content-type')), 'Reasonable Content-Type for '. $type .' (actual: '. $t .')');
        }
        unset($r);
    }

}
