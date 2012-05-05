<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Numbers_Roman_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

chdir(dirname(__FILE__) . '/../');
require_once 'Numbers_RomanTest.php';


class Numbers_Roman_AllTests
{
    public static function main()
    {

        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Numbers_Roman tests');
        /** Add testsuites, if there is. */
        $suite->addTestSuite('Numbers_RomanTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Numbers_Roman_AllTests::main') {
    Numbers_Roman_AllTests::main();
}
?>