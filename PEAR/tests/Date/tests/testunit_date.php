<?php
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 Marshall Roch                                |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pear-dev@lists.php.net so we can mail you a copy immediately.        |
// +----------------------------------------------------------------------+
// | Author: Marshall Roch <mroch@php.net>                                |
// +----------------------------------------------------------------------+
//
// $Id: testunit_date.php,v 1.8 2008/03/22 23:44:23 c01234 Exp $
//

require_once 'Date.php';
require_once 'PHPUnit.php';

class myDate extends Date {
    function myDate($date)
    {
        $this->Date($date);
    }
}

/**
 * Test case for Date
 *
 * @package Date
 * @author Marshall Roch <mroch@php.net>
 */
class Date_Test extends PHPUnit_TestCase {

    var $time;

    function Date_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->time = new Date("2003-10-04 14:03:24Z");
    }

    function tearDown()
    {
        unset($this->time);
    }

    function testDateNull()
    {
        $time = new Date();
        $this->assertEquals(
            date('Y-m-d H:i:s'),
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $time->year, $time->month, $time->day,
                $time->hour, $time->minute, $time->second)
        );
    }

    function testAbstraction()
    {
        $d = new Date();
        $my = new myDate($d);
        $this->assertEquals($d->getDate(),$my->getDate());
    }

    function testDateCopy()
    {
        $temp = new Date($this->time);
        $this->assertEquals($temp, $this->time);
    }

    function testDateISO()
    {
        $temp = new Date("2003-10-04 14:03:24");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testDateISOBasic()
    {
        $temp = new Date("20031004T140324");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testDateISOExtended()
    {
        $temp = new Date("2003-10-04T14:03:24");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testDateISOTimestamp()
    {
        $temp = new Date("20031004140324");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testDateUnixtime()
    {
        $temp = new Date();
        $temp->setTZbyID("UTC");
        $temp->setDate(strtotime("2003-10-04 14:03:24Z"));
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testDateUnixtime2()
    {
        $temp = new Date();
        $temp->setTZbyID("UTC-05:30");
        $temp->setDate(strtotime("2003-10-04 14:03:24Z"));
        $temp->convertTZbyID("UTC");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testDateUnixtime3()
    {
        $temp = new Date();
        $temp->setTZbyID("America/Chicago");
        $temp->setDate(strtotime("2003-10-04 14:03:24Z"));
        $temp->convertTZbyID("UTC");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testDateUnixtime4()
    {
        $temp = new Date();
        $temp->setTZbyID("Europe/London");
        $temp->setDate(strtotime("2003-10-04 14:03:24Z")); // Summer time in London
        $temp->setTZbyID("UTC");
        $this->assertEquals(
            '2003-10-04 15:03:24', // Preserves London local time (15.03)
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $temp->year, $temp->month, $temp->day,
                $temp->hour, $temp->minute, $temp->second)
        );
    }

    function testSetDateISO()
    {
        $this->time->setDate("2003-10-04 14:03:24");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $this->time->year, $this->time->month, $this->time->day,
                $this->time->hour, $this->time->minute, $this->time->second)
        );
    }

    function testSetDateISOBasic()
    {
        $this->time->setDate("20031004T140324");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $this->time->year, $this->time->month, $this->time->day,
                $this->time->hour, $this->time->minute, $this->time->second)
        );
    }

    function testSetDateISOExtended()
    {
        $this->time->setDate("2003-10-04T14:03:24");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $this->time->year, $this->time->month, $this->time->day,
                $this->time->hour, $this->time->minute, $this->time->second)
        );
    }

    function testSetDateTimestamp()
    {
        $this->time->setDate("20031004140324");
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $this->time->year, $this->time->month, $this->time->day,
                $this->time->hour, $this->time->minute, $this->time->second)
        );
    }

    function testSetDateUnixtime()
    {
        $this->time->setDate(strtotime("2003-10-04 14:03:24Z"));
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $this->time->year, $this->time->month, $this->time->day,
                $this->time->hour, $this->time->minute, $this->time->second)
        );
    }

    function testSetDateUnixtime2()
    {
        $hs_oldtz = $this->time->getTZID();
        $this->time->setTZbyID("UTC-05:30");
        $this->time->setDate(strtotime("2003-10-04 14:03:24Z"));
        $this->time->convertTZbyID($hs_oldtz);
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $this->time->year, $this->time->month, $this->time->day,
                $this->time->hour, $this->time->minute, $this->time->second)
        );
    }

    function testSetDateUnixtime3()
    {
        $hs_oldtz = $this->time->getTZID();
        $this->time->setTZbyID("America/Chicago");
        $this->time->setDate(strtotime("2003-10-04 14:03:24Z"));
        $this->time->convertTZbyID($hs_oldtz);
        $this->assertEquals(
            '2003-10-04 14:03:24',
            sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $this->time->year, $this->time->month, $this->time->day,
                $this->time->hour, $this->time->minute, $this->time->second)
        );
    }

    function testGetDateISO()
    {
        $date = $this->time->getDate(DATE_FORMAT_ISO);
        $this->assertEquals('2003-10-04 14:03:24', $date);
    }

    function testGetDateISOBasic()
    {
        $date = $this->time->getDate(DATE_FORMAT_ISO_BASIC);
        $this->assertEquals('20031004T140324Z', $date);
    }

    function testGetDateISOExtended()
    {
        $date = $this->time->getDate(DATE_FORMAT_ISO_EXTENDED);
        $this->assertEquals('2003-10-04T14:03:24Z', $date);
    }

    function testGetDateTimestamp()
    {
        $date = $this->time->getDate(DATE_FORMAT_TIMESTAMP);
        $this->assertEquals('20031004140324', $date);
    }

    function testGetDateUnixtime()
    {
        $date = $this->time->getDate(DATE_FORMAT_UNIXTIME);
        $this->assertEquals(strtotime('2003-10-04 14:03:24Z'), $date);
    }

    function testGetDateUnixtime2()
    {
        $hs_oldtz = $this->time->getTZID();
        $this->time->convertTZbyID("UTC-05:30");
        $date = $this->time->getDate(DATE_FORMAT_UNIXTIME);
        $this->assertEquals(strtotime('2003-10-04 14:03:24Z'), $date);
        $this->time->convertTZbyID($hs_oldtz);
    }

    function testGetDateUnixtime3()
    {
        $hs_oldtz = $this->time->getTZID();
        $this->time->convertTZbyID("America/Chicago");
        $date = $this->time->getDate(DATE_FORMAT_UNIXTIME);
        $this->assertEquals(strtotime('2003-10-04 14:03:24Z'), $date);
        $this->time->convertTZbyID($hs_oldtz);
    }

    function testFormat()
    {
        $codes = array(
            'a' => 'Sat',
            'A' => 'Saturday',
            'b' => 'Oct',
            'B' => 'October',
            'C' => '20',
            'd' => '04',
            'D' => '10/04/2003',
            'e' => '4',
            'H' => '14',
            'I' => '02',
            'j' => '277',
            'm' => '10',
            'M' => '03',
            'n' => "\n",
            'O' => '+00:00',
            'o' => '+00:00',
            'p' => 'pm',
            'P' => 'PM',
            'r' => '02:03:24 PM',
            'R' => '14:03',
            'S' => '24',
            't' => "\t",
            'T' => '14:03:24',
            'w' => '6',
            'U' => '39',
            'y' => '03',
            'Y' => '2003',
            '%' => '%'
        );

        foreach ($codes as $code => $expected) {
            $this->assertEquals(
                "$code: $expected", $this->time->format("$code: %$code")
            );
        }
    }

    function testToUTCbyOffset()
    {
        $this->time->setTZbyID('EST');
        $this->time->toUTC();
        $temp = new Date("2003-10-04 14:03:24");
        $temp->toUTCbyOffset("-05:00");

        $this->assertEquals($temp, $this->time);
    }

}

// runs the tests
$suite = new PHPUnit_TestSuite("Date_Test");
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();

?>
