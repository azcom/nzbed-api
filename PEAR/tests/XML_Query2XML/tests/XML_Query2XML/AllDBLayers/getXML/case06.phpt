--TEST--
XML_Query2XML::getXML(): Case06
--SKIPIF--
<?php
require_once dirname(dirname(__FILE__)) . '/skipif.php';
if (strpos(DSN, 'sqlite') === 0 && strpos(DSN, 'sqlite3') !== 0) {
    echo 'skip sqlite before v3.1 does not support backreferences to fields in parent table - see http://www.sqlite.org/cvstrac/wiki?p=UnsupportedSql';
    exit;
}
?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once 'XML/Query2XML/Callback.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';

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

/**Command class that implements the command pattern.
* It implements the XML_Query2XML_Callback interface
* and therefore has to provide the public non-static
* method execute(array $record).
*/
class UppercaseColumnCommand implements XML_Query2XML_Callback
{
    public function __construct($columnName)
    {
        $this->_columnName = $columnName;
    }
    public function execute(array $record)
    {
        return strtoupper($record[$this->_columnName]);
    }
}

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
                    'city' => new UppercaseColumnCommand('city'),
                    'street',
                    'phone'
                )
            ),
            'department' => array(
                'idColumn' => 'departmentid',
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
                        'attributes' => array(
                            'employeeid'
                        ),
                        'elements' => array(
                            'employeename',
                            'sales' => array(
                                'rootTag' => 'sales',
                                'rowTag' => 'sale',
                                'idColumn' => 'saleid',
                                'attributes' => array(
                                    'saleid'
                                ),
                                'elements' => array(
                                    'timestamp',
                                    'customer' => array(
                                        'idColumn' => 'customerid',
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
                                        'attributes' => array(
                                            'albumid'
                                        ),
                                        'elements' => array(
                                            'title',
                                            'published_year',
                                            'comment' => '?#Helper::summarizeComment(12)',
                                            'artist' => array(
                                                'idColumn' => 'artistid',
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
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_company date_generated="2005-08-23T14:52:50">
  <store storeid="1">
    <store_sales>10</store_sales>
    <store_employees>6</store_employees>
    <manager manager_employeeid="1">
      <manager_employeename>Michael Jones</manager_employeename>
    </manager>
    <address>
      <country>US</country>
      <state>NY</state>
      <city>NEW YORK</city>
      <street>Broadway &amp; 72nd Str</street>
      <phone>123 456 7890</phone>
    </address>
    <department departmentid="1">
      <department_sales>10</department_sales>
      <department_employees>3</department_employees>
      <departmentname>Sales</departmentname>
      <department_head department_head_employeeid="1">
        <department_head_employeename>Michael Jones</department_head_employeename>
      </department_head>
      <employees>
        <employee employeeid="1">
          <employeename>Michael Jones</employeename>
          <sales>
            <sale saleid="1">
              <timestamp>2005-05-25 16:32:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="7">
              <timestamp>2005-07-10 15:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="16">
              <timestamp>2005-06-05 12:56:12</timestamp>
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
                  <birth_year>1942</birth_year>
                  <birth_place>Tennessee</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="19">
              <timestamp>2005-07-10 16:03:01</timestamp>
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
                  <birth_year>1942</birth_year>
                  <birth_place>Tennessee</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee employeeid="2">
          <employeename>Susi Weintraub</employeename>
          <sales>
            <sale saleid="3">
              <timestamp>2005-07-10 11:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="9">
              <timestamp>2005-07-10 18:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="17">
              <timestamp>2005-07-10 10:03:32</timestamp>
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
                  <birth_year>1942</birth_year>
                  <birth_place>Tennessee</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="20">
              <timestamp>2005-07-10 19:03:50</timestamp>
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
                  <birth_year>1942</birth_year>
                  <birth_place>Tennessee</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee employeeid="3">
          <employeename>Steve Hack</employeename>
          <sales>
            <sale saleid="5">
              <timestamp>2005-07-10 13:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="18">
              <timestamp>2005-07-10 14:03:52</timestamp>
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
                  <birth_year>1942</birth_year>
                  <birth_place>Tennessee</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
      </employees>
    </department>
    <department departmentid="2">
      <department_sales>0</department_sales>
      <department_employees>3</department_employees>
      <departmentname>Marketing</departmentname>
      <department_head department_head_employeeid="4">
        <department_head_employeename>Joan Kerr</department_head_employeename>
      </department_head>
      <employees>
        <employee employeeid="4">
          <employeename>Joan Kerr</employeename>
          <sales/>
        </employee>
        <employee employeeid="5">
          <employeename>Marcus Roth</employeename>
          <sales/>
        </employee>
        <employee employeeid="6">
          <employeename>Jack Mack</employeename>
          <sales/>
        </employee>
      </employees>
    </department>
  </store>
  <store storeid="2">
    <store_sales>10</store_sales>
    <store_employees>6</store_employees>
    <manager manager_employeeid="2">
      <manager_employeename>Susi Weintraub</manager_employeename>
    </manager>
    <address>
      <country>US</country>
      <state>NY</state>
      <city>LARCHMONT</city>
      <street>Palmer Ave 71</street>
      <phone>456 7890</phone>
    </address>
    <department departmentid="3">
      <department_sales>10</department_sales>
      <department_employees>3</department_employees>
      <departmentname>Sales</departmentname>
      <department_head department_head_employeeid="7">
        <department_head_employeename>Rita Doktor</department_head_employeename>
      </department_head>
      <employees>
        <employee employeeid="7">
          <employeename>Rita Doktor</employeename>
          <sales>
            <sale saleid="2">
              <timestamp>2005-06-05 12:56:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="8">
              <timestamp>2005-07-10 16:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="11">
              <timestamp>2005-05-25 16:23:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="14">
              <timestamp>2005-07-10 15:09:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee employeeid="8">
          <employeename>David Til</employeename>
          <sales>
            <sale saleid="4">
              <timestamp>2005-07-10 10:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="10">
              <timestamp>2005-07-10 19:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="12">
              <timestamp>2005-07-10 11:56:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="15">
              <timestamp>2005-07-10 18:49:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
        <employee employeeid="9">
          <employeename>Pia Eist</employeename>
          <sales>
            <sale saleid="6">
              <timestamp>2005-07-10 14:03:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
            <sale saleid="13">
              <timestamp>2005-07-10 13:12:00</timestamp>
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
                  <birth_year>1920</birth_year>
                  <birth_place>Chicago</birth_place>
                  <genre>Soul</genre>
                </artist>
              </album>
            </sale>
          </sales>
        </employee>
      </employees>
    </department>
    <department departmentid="4">
      <department_sales>0</department_sales>
      <department_employees>3</department_employees>
      <departmentname>Marketing</departmentname>
      <department_head department_head_employeeid="10">
        <department_head_employeename>Hanna Poll</department_head_employeename>
      </department_head>
      <employees>
        <employee employeeid="10">
          <employeename>Hanna Poll</employeename>
          <sales/>
        </employee>
        <employee employeeid="11">
          <employeename>Jim Wells</employeename>
          <sales/>
        </employee>
        <employee employeeid="12">
          <employeename>Sandra Wilson</employeename>
          <sales/>
        </employee>
      </employees>
    </department>
  </store>
</music_company>
