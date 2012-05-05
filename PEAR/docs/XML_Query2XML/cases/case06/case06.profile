FROM_DB FROM_CACHE CACHED AVG_DURATION DURATION_SUM SQL
1       0          false  0.0321409702 0.0321409702 SELECT
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
        ar.artistid

TOTAL_DURATION: 0.14400696754456
DB_DURATION:    0.13093209266663
