--TEST--
XML_Query2XML::getXML(): Case08
--SKIPIF--
<?php
require_once dirname(dirname(__FILE__)) . '/skipif.php';
if (!@include_once 'I18N/UnicodeString.php') {
    print 'skip could not find I18N/UnicodeString.php';
    exit;
}
if (strpos(DSN, 'sqlite') === 0 && strpos(DSN, 'sqlite3') !== 0) {
    echo 'skip sqlite before v3.1 does not support backreferences to fields in parent table - see http://www.sqlite.org/cvstrac/wiki?p=UnsupportedSql';
    exit;
}
?>
--FILE--
<?php
class Mappers
{
    public static function departmentMapper($str)
    {
        //maps 'one_two_three' to 'oneTwoThree'
        return preg_replace("/(_)([a-z])/e", "strtoupper('\\2')", $str);
    }
    
    public static function employeeMapper($str)
    {
        //maps 'one_two_three' to 'OneTwoThree'
        return ucfirst(preg_replace("/(_)([a-z])/e", "strtoupper('\\2')", $str));
    }
    
    public function saleMapper($str)
    {
        //maps 'one_two_three' to 'ONETWOTHREE'
        return strtoupper(str_replace('_', '', $str));
    }
}

function mapArtist($str)
{
    //maps 'one_two_three' to 'onetwothree'
    return strtolower(str_replace('_', '', $str));
}

$myMappers = new Mappers();

require_once 'XML/Query2XML.php';
require_once 'XML/Query2XML/ISO9075Mapper.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
$dom = $query2xml->getXML(
    "SELECT
         s.*,
         manager.employeeid AS manager_employeeid,
         manager.employeename AS manager_employeename,
         d.*,
         department_head.employeeid AS department_head_employeeid,
         department_head.employeename AS department_head_employeename,
         e.*,
         sa.*,
         c.*,
         al.*,
         ar.*,
         (SELECT COUNT(*) FROM sale WHERE sale.store_id = s.storeid) AS store_sales,
         (SELECT
            COUNT(*)
          FROM
            sale, employee, employee_department
          WHERE
            sale.employee_id = employee.employeeid
            AND
            employee_department.employee_id = employee.employeeid
            AND
            employee_department.department_id = d.departmentid
         ) AS department_sales,
         (SELECT
            COUNT(*)
          FROM
            employee, employee_department, department
          WHERE
            employee_department.employee_id = employee.employeeid
            AND
            employee_department.department_id = department.departmentid
            AND
            department.store_id = s.storeid
         ) AS store_employees,
         (SELECT
            COUNT(*)
          FROM
            employee, employee_department
          WHERE
            employee_department.employee_id = employee.employeeid
            AND
            employee_department.department_id = d.departmentid
         ) AS department_employees
     FROM
         store s
          LEFT JOIN employee manager ON s.manager = manager.employeeid
         LEFT JOIN department d ON d.store_id = s.storeid
          LEFT JOIN employee department_head ON department_head.employeeid = d.department_head
          LEFT JOIN employee_department ed ON ed.department_id = d.departmentid
           LEFT JOIN employee e ON e.employeeid = ed.employee_id
            LEFT JOIN sale sa ON sa.employee_id = e.employeeid
             LEFT JOIN customer c ON c.customerid = sa.customer_id
             LEFT JOIN album al ON al.albumid = sa.album_id
              LEFT JOIN artist ar ON ar.artistid = al.artist_id
     ORDER BY
        s.storeid,
        manager.employeeid,
        d.departmentid,
        department_head.employeeid,
        ed.employee_id,
        ed.department_id,
        e.employeeid,
        sa.saleid,
        c.customerid,
        al.albumid,
        ar.artistid",
    array(
        'rootTag' => 'music_company',
        'rowTag' => 'store',
        'idColumn' => 'storeid',
        'mapper' => 'strtoupper',
        'attributes' => array(
            'storeid'
        ),
        'elements' => array(
            'store_sales',
            'store_employees',
            'manager' => array(
                'idColumn' => 'manager_employeeid',
                'attributes' => array(
                    'manager_employeeid'
                ),
                'elements' => array(
                    'manager_employeename'
                )
            ),
            'address' => array(
                'elements' => array(
                    'country',
                    'state' => '#Helper::getStatePostalCode()',
                    'city',
                    'street',
                    'phone'
                )
            ),
            'department' => array(
                'idColumn' => 'departmentid',
                'mapper' => 'Mappers::departmentMapper',
                'attributes' => array(
                    'departmentid'
                ),
                'elements' => array(
                    'department_sales',
                    'department_employees',
                    'departmentname',
                    'department_head' => array(
                        'idColumn' => 'department_head_employeeid',
                        'attributes' => array(
                            'department_head_employeeid'
                        ),
                        'elements' => array(
                            'department_head_employeename'
                        )
                    ),
                    'employees' => array(
                        'rootTag' => 'employees',
                        'rowTag' => 'employee',
                        'idColumn' => 'employeeid',
                        'mapper' => array('Mappers', 'employeeMapper'),
                        'attributes' => array(
                            'employeeid'
                        ),
                        'elements' => array(
                            'employeename',
                            'sales' => array(
                                'rootTag' => 'sales',
                                'rowTag' => 'sale',
                                'idColumn' => 'saleid',
                                'mapper' => array($myMappers, 'saleMapper'),
                                'attributes' => array(
                                    'saleid'
                                ),
                                'elements' => array(
                                    'timestamp',
                                    'customer' => array(
                                        'idColumn' => 'customerid',
                                        'mapper' => false,
                                        'attributes' => array(
                                            'customerid'
                                        ),
                                        'elements' => array(
                                            'first_name',
                                            'last_name',
                                            'email'
                                        )
                                    ),
                                    'album' => array(
                                        'idColumn' => 'albumid',
                                        'mapper' => 'XML_Query2XML_ISO9075Mapper::map',
                                        'attributes' => array(
                                            'albumid'
                                        ),
                                        'elements' => array(
                                            'title',
                                            'published_year',
                                            'comment' => '?#Helper::summarizeComment(12)',
                                            'artist' => array(
                                                'idColumn' => 'artistid',
                                                'mapper' => 'mapArtist',
                                                'attributes' => array(
                                                    'artistid'
                                                ),
                                                'elements' => array(
                                                    'name',
                                                    'birth_year',
                                                    'birth_place',
                                                    'genre'
                                                )
                                            )
                                        ) // album elements
                                    ) //album array
                                ) //sales elements
                            ) //sales array
                        ) //employees elements
                    ) //employees array
                ) //department elements
            ) // department array
        ) //root elements
    ) //root
); //getXML method call

$root = $dom->firstChild;
$root->setAttribute('date_generated', '2005-08-23T14:52:50');

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();



/**Static class that provides validation and parsing methods for
* generating XML.
*
* It is static so that we can easyly call its methods from inside
* Query2XML using eval'd code.
*/
class Helper
{
    /**Associative array of US postal state codes*/
    public static $statePostalCodes = array(
        'ALABAMA' => 'AL', 'ALASKA' => 'AK', 'AMERICAN SAMOA' => 'AS', 'ARIZONA' => 'AZ', 'ARKANSAS' => 'AR', 'CALIFORNIA' => 'CA',
        'COLORADO' => 'CO', 'CONNECTICUT' => 'CT', 'DELAWARE' => 'DE', 'DISTRICT OF COLUMBIA' => 'DC', 'FEDERATED STATES OF MICRONESIA' => 'FM',
        'FLORIDA' => 'FL', 'GEORGIA' => 'GA', 'GUAM' => 'GU', 'HAWAII' => 'HI', 'IDAHO' => 'ID', 'ILLINOIS' => 'IL', 'INDIANA' => 'IN',
        'IOWA' => 'IA', 'KANSAS' => 'KS', 'KENTUCKY' => 'KY', 'LOUISIANA' => 'LA', 'MAINE' => 'ME', 'MARSHALL ISLANDS' => 'MH', 'MARYLAND' => 'MD',
        'MASSACHUSETTS' => 'MA', 'MICHIGAN' => 'MI', 'MINNESOTA' => 'MN', 'MISSISSIPPI' => 'MS', 'MISSOURI' => 'MO', 'MONTANA' => 'MT',
        'NEBRASKA' => 'NE', 'NEVADA' => 'NV', 'NEW HAMPSHIRE' => 'NH', 'NEW JERSEY' => 'NJ', 'NEW JESEY' => 'NJ', 'NEW MEXICO' => 'NM', 'NEW YORK' => 'NY',
        'NORTH CAROLINA' => 'NC', 'NORTH DAKOTA' => 'ND', 'NORTHERN MARIANA ISLANDS' => 'MP', 'OHIO' => 'OH', 'OKLAHOMA' => 'OK', 'OREGON' => 'OR',
        'PALAU' => 'PW', 'PENNSYLVANIA' => 'PA', 'PUERTO RICO' => 'PR', 'RHODE ISLAND' => 'RI', 'SOUTH CAROLINA' => 'SC', 'SOUTH DAKOTA' => 'SD',
        'TENNESSEE' => 'TN', 'TEXAS' => 'TX', 'UTAH' => 'UT', 'VERMONT' => 'VT', 'VIRGIN ISLANDS' => 'VI', 'VIRGINIA' => 'VA', 'WASHINGTON' => 'WA',
        'WEST VIRGINIA' => 'WV', 'WISCONSIN' => 'WI', 'WYOMING' => 'WY'
    );
            
    /**Translates a US state name into its two-letter postal code.
    * If the translation fails, $state is returned unchanged
    * @param $record The record
    */
    public static function getStatePostalCode($record)
    {
        $state = $record["state"];
        $s = str_replace("  ", " ", trim(strtoupper($state)));
        if (isset(self::$statePostalCodes[$s])) {
            return self::$statePostalCodes[$s];
        } else {
            return $state;
        }
    }
      
    function summarize($str, $limit=50, $appendString=' ...')
    {
        if (strlen($str) > $limit) {
            $str = substr($str, 0, $limit - strlen($appendString)) . $appendString;
        }
        return $str;
    }
    
    
    function summarizeComment($record, $limit)
    {
        return self::summarize($record["comment"], $limit);
    }
}
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_company date_generated="2005-08-23T14:52:50">
  <store STOREID="1">
    <STORE_SALES>10</STORE_SALES>
    <STORE_EMPLOYEES>6</STORE_EMPLOYEES>
    <manager MANAGER_EMPLOYEEID="1">
      <MANAGER_EMPLOYEENAME>Michael Jones</MANAGER_EMPLOYEENAME>
    </manager>
    <address>
      <COUNTRY>US</COUNTRY>
      <STATE>NY</STATE>
      <CITY>New York</CITY>
      <STREET>Broadway &amp; 72nd Str</STREET>
      <PHONE>123 456 7890</PHONE>
    </address>
    <department departmentid="1">
      <departmentSales>10</departmentSales>
      <departmentEmployees>3</departmentEmployees>
      <departmentname>Sales</departmentname>
      <department_head departmentHeadEmployeeid="1">
        <departmentHeadEmployeename>Michael Jones</departmentHeadEmployeename>
      </department_head>
      <employees>
        <employee Employeeid="1">
          <Employeename>Michael Jones</Employeename>
          <sales>
            <sale SALEID="1">
              <TIMESTAMP>2005-05-25 16:32:00</TIMESTAMP>
              <customer customerid="1">
                <first_name>Jane</first_name>
                <last_name>Doe</last_name>
                <email>jane.doe@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="7">
              <TIMESTAMP>2005-07-10 15:03:00</TIMESTAMP>
              <customer customerid="7">
                <first_name>Nick</first_name>
                <last_name>Fallow</last_name>
                <email>nick.fallow@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="16">
              <TIMESTAMP>2005-06-05 12:56:12</TIMESTAMP>
              <customer customerid="2">
                <first_name>John</first_name>
                <last_name>Doe</last_name>
                <email>john.doe@example.com</email>
              </customer>
              <album albumid="3">
                <title>Shaft</title>
                <published_year>1972</published_year>
                <comment>he's the man</comment>
                <artist artistid="2">
                  <name>Isaac Hayes</name>
                  <birthyear>1942</birthyear>
                  <birthplace>Tennessee</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="19">
              <TIMESTAMP>2005-07-10 16:03:01</TIMESTAMP>
              <customer customerid="8">
                <first_name>Ed</first_name>
                <last_name>Burton</last_name>
                <email>ed.burton@example.com</email>
              </customer>
              <album albumid="3">
                <title>Shaft</title>
                <published_year>1972</published_year>
                <comment>he's the man</comment>
                <artist artistid="2">
                  <name>Isaac Hayes</name>
                  <birthyear>1942</birthyear>
                  <birthplace>Tennessee</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee Employeeid="2">
          <Employeename>Susi Weintraub</Employeename>
          <sales>
            <sale SALEID="3">
              <TIMESTAMP>2005-07-10 11:03:00</TIMESTAMP>
              <customer customerid="3">
                <first_name>Susan</first_name>
                <last_name>Green</last_name>
                <email>susan.green@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="9">
              <TIMESTAMP>2005-07-10 18:03:00</TIMESTAMP>
              <customer customerid="9">
                <first_name>Jack</first_name>
                <last_name>Woo</last_name>
                <email>jack.woo@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="17">
              <TIMESTAMP>2005-07-10 10:03:32</TIMESTAMP>
              <customer customerid="4">
                <first_name>Victoria</first_name>
                <last_name>Alt</last_name>
                <email>victory.alt@example.com</email>
              </customer>
              <album albumid="3">
                <title>Shaft</title>
                <published_year>1972</published_year>
                <comment>he's the man</comment>
                <artist artistid="2">
                  <name>Isaac Hayes</name>
                  <birthyear>1942</birthyear>
                  <birthplace>Tennessee</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="20">
              <TIMESTAMP>2005-07-10 19:03:50</TIMESTAMP>
              <customer customerid="10">
                <first_name>Maria</first_name>
                <last_name>Gonzales</last_name>
                <email>maria.gonzales@example.com</email>
              </customer>
              <album albumid="3">
                <title>Shaft</title>
                <published_year>1972</published_year>
                <comment>he's the man</comment>
                <artist artistid="2">
                  <name>Isaac Hayes</name>
                  <birthyear>1942</birthyear>
                  <birthplace>Tennessee</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee Employeeid="3">
          <Employeename>Steve Hack</Employeename>
          <sales>
            <sale SALEID="5">
              <TIMESTAMP>2005-07-10 13:03:00</TIMESTAMP>
              <customer customerid="5">
                <first_name>Will</first_name>
                <last_name>Rippy</last_name>
                <email>will.wippy@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="18">
              <TIMESTAMP>2005-07-10 14:03:52</TIMESTAMP>
              <customer customerid="6">
                <first_name>Tim</first_name>
                <last_name>Raw</last_name>
                <email>tim.raw@example.com</email>
              </customer>
              <album albumid="3">
                <title>Shaft</title>
                <published_year>1972</published_year>
                <comment>he's the man</comment>
                <artist artistid="2">
                  <name>Isaac Hayes</name>
                  <birthyear>1942</birthyear>
                  <birthplace>Tennessee</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
      </employees>
    </department>
    <department departmentid="2">
      <departmentSales>0</departmentSales>
      <departmentEmployees>3</departmentEmployees>
      <departmentname>Marketing</departmentname>
      <department_head departmentHeadEmployeeid="4">
        <departmentHeadEmployeename>Joan Kerr</departmentHeadEmployeename>
      </department_head>
      <employees>
        <employee Employeeid="4">
          <Employeename>Joan Kerr</Employeename>
          <sales/>
        </employee>
        <employee Employeeid="5">
          <Employeename>Marcus Roth</Employeename>
          <sales/>
        </employee>
        <employee Employeeid="6">
          <Employeename>Jack Mack</Employeename>
          <sales/>
        </employee>
      </employees>
    </department>
  </store>
  <store STOREID="2">
    <STORE_SALES>10</STORE_SALES>
    <STORE_EMPLOYEES>6</STORE_EMPLOYEES>
    <manager MANAGER_EMPLOYEEID="2">
      <MANAGER_EMPLOYEENAME>Susi Weintraub</MANAGER_EMPLOYEENAME>
    </manager>
    <address>
      <COUNTRY>US</COUNTRY>
      <STATE>NY</STATE>
      <CITY>Larchmont</CITY>
      <STREET>Palmer Ave 71</STREET>
      <PHONE>456 7890</PHONE>
    </address>
    <department departmentid="3">
      <departmentSales>10</departmentSales>
      <departmentEmployees>3</departmentEmployees>
      <departmentname>Sales</departmentname>
      <department_head departmentHeadEmployeeid="7">
        <departmentHeadEmployeename>Rita Doktor</departmentHeadEmployeename>
      </department_head>
      <employees>
        <employee Employeeid="7">
          <Employeename>Rita Doktor</Employeename>
          <sales>
            <sale SALEID="2">
              <TIMESTAMP>2005-06-05 12:56:00</TIMESTAMP>
              <customer customerid="2">
                <first_name>John</first_name>
                <last_name>Doe</last_name>
                <email>john.doe@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="8">
              <TIMESTAMP>2005-07-10 16:03:00</TIMESTAMP>
              <customer customerid="8">
                <first_name>Ed</first_name>
                <last_name>Burton</last_name>
                <email>ed.burton@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="11">
              <TIMESTAMP>2005-05-25 16:23:00</TIMESTAMP>
              <customer customerid="1">
                <first_name>Jane</first_name>
                <last_name>Doe</last_name>
                <email>jane.doe@example.com</email>
              </customer>
              <album albumid="2">
                <title>Curtis</title>
                <published_year>1970</published_year>
                <comment>that man ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="14">
              <TIMESTAMP>2005-07-10 15:09:00</TIMESTAMP>
              <customer customerid="7">
                <first_name>Nick</first_name>
                <last_name>Fallow</last_name>
                <email>nick.fallow@example.com</email>
              </customer>
              <album albumid="2">
                <title>Curtis</title>
                <published_year>1970</published_year>
                <comment>that man ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee Employeeid="8">
          <Employeename>David Til</Employeename>
          <sales>
            <sale SALEID="4">
              <TIMESTAMP>2005-07-10 10:03:00</TIMESTAMP>
              <customer customerid="4">
                <first_name>Victoria</first_name>
                <last_name>Alt</last_name>
                <email>victory.alt@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="10">
              <TIMESTAMP>2005-07-10 19:03:00</TIMESTAMP>
              <customer customerid="10">
                <first_name>Maria</first_name>
                <last_name>Gonzales</last_name>
                <email>maria.gonzales@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="12">
              <TIMESTAMP>2005-07-10 11:56:00</TIMESTAMP>
              <customer customerid="3">
                <first_name>Susan</first_name>
                <last_name>Green</last_name>
                <email>susan.green@example.com</email>
              </customer>
              <album albumid="2">
                <title>Curtis</title>
                <published_year>1970</published_year>
                <comment>that man ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="15">
              <TIMESTAMP>2005-07-10 18:49:00</TIMESTAMP>
              <customer customerid="9">
                <first_name>Jack</first_name>
                <last_name>Woo</last_name>
                <email>jack.woo@example.com</email>
              </customer>
              <album albumid="2">
                <title>Curtis</title>
                <published_year>1970</published_year>
                <comment>that man ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee Employeeid="9">
          <Employeename>Pia Eist</Employeename>
          <sales>
            <sale SALEID="6">
              <TIMESTAMP>2005-07-10 14:03:00</TIMESTAMP>
              <customer customerid="6">
                <first_name>Tim</first_name>
                <last_name>Raw</last_name>
                <email>tim.raw@example.com</email>
              </customer>
              <album albumid="1">
                <title>New World Order</title>
                <published_year>1990</published_year>
                <comment>the best ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale SALEID="13">
              <TIMESTAMP>2005-07-10 13:12:00</TIMESTAMP>
              <customer customerid="5">
                <first_name>Will</first_name>
                <last_name>Rippy</last_name>
                <email>will.wippy@example.com</email>
              </customer>
              <album albumid="2">
                <title>Curtis</title>
                <published_year>1970</published_year>
                <comment>that man ...</comment>
                <artist artistid="1">
                  <name>Curtis Mayfield</name>
                  <birthyear>1920</birthyear>
                  <birthplace>Chicago</birthplace>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
      </employees>
    </department>
    <department departmentid="4">
      <departmentSales>0</departmentSales>
      <departmentEmployees>3</departmentEmployees>
      <departmentname>Marketing</departmentname>
      <department_head departmentHeadEmployeeid="10">
        <departmentHeadEmployeename>Hanna Poll</departmentHeadEmployeename>
      </department_head>
      <employees>
        <employee Employeeid="10">
          <Employeename>Hanna Poll</Employeename>
          <sales/>
        </employee>
        <employee Employeeid="11">
          <Employeename>Jim Wells</Employeename>
          <sales/>
        </employee>
        <employee Employeeid="12">
          <Employeename>Sandra Wilson</Employeename>
          <sales/>
        </employee>
      </employees>
    </department>
  </store>
</music_company>
