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
require_once 'MDB2.php';
$query2xml = XML_Query2XML::factory(MDB2::factory('mysql://root@localhost/Query2XML_Tests'));
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