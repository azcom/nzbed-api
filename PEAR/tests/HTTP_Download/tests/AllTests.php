<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'HTML_Download_AllTests::main');
}

require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'HTML_DownloadTest.php';

class HTML_Download_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PEAR - HTML_Download');

        $suite->addTestSuite('HTML_DownloadTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'HTML_Download_AllTests::main') {
    HTML_Download_AllTests::main();
}
