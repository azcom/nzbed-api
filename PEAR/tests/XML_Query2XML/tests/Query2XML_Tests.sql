# 
# This is the SQL DDL for the database used by all unit tests and examples
# within the tutorial.
# Version: $Id: Query2XML_Tests.sql,v 1.3 2006/12/26 23:35:00 lukasfeiler Exp $
#

CREATE DATABASE Query2XML_Tests;
USE Query2XML_Tests;


CREATE TABLE artist (
	artistid INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255),
	birth_year Int,
	birth_place VARCHAR(255),
	genre VARCHAR(255),
	UNIQUE (artistid),
    PRIMARY KEY (artistid)
);

CREATE TABLE customer (
	customerid INT NOT NULL AUTO_INCREMENT,
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	email VARCHAR(255),
	UNIQUE (customerid),
    PRIMARY KEY (customerid)
);

CREATE TABLE album (
	albumid INT NOT NULL AUTO_INCREMENT,
	artist_id INT NOT NULL,
	title VARCHAR(255),
	published_year Int,
	comment VARCHAR(255),
	UNIQUE (albumid),
    PRIMARY KEY (albumid),
    FOREIGN KEY (artist_id) REFERENCES artist (artistid)
);

CREATE TABLE employee (
	employeeid INT NOT NULL AUTO_INCREMENT,
	employeename VARCHAR(255),
	UNIQUE (employeeid),
    PRIMARY KEY (employeeid)
);

CREATE TABLE store (
	storeid INT NOT NULL AUTO_INCREMENT,
	manager INT NOT NULL,
	country VARCHAR(255),
	state VARCHAR(255),
	city VARCHAR(255),
	street VARCHAR(255),
	phone VARCHAR(255),
	building_xmldata TEXT,
	UNIQUE (storeid),
    PRIMARY KEY (storeid),
    FOREIGN KEY (manager) REFERENCES employee (employeeid)
);

CREATE TABLE department (
	departmentid INT NOT NULL AUTO_INCREMENT,
	store_id INT NOT NULL,
	department_head INT NOT NULL,
	departmentname VARCHAR(255),
	UNIQUE (departmentid),
    PRIMARY KEY (departmentid),
    FOREIGN KEY (department_head) REFERENCES employee (employeeid),
    FOREIGN KEY (store_id) REFERENCES store (storeid)
);

CREATE TABLE employee_department (
	employee_id INT NOT NULL,
	department_id INT NOT NULL,
    PRIMARY KEY (employee_id,department_id),
    FOREIGN KEY (employee_id) REFERENCES employee (employeeid),
    FOREIGN KEY (department_id) REFERENCES department (departmentid)
);

CREATE TABLE sale (
	saleid INT NOT NULL AUTO_INCREMENT,
	album_id INT NOT NULL,
	customer_id INT NOT NULL,
	employee_id INT NOT NULL,
	store_id INT NOT NULL,
	timestamp Timestamp(14),
	UNIQUE (saleid),
    PRIMARY KEY (saleid),
    FOREIGN KEY (employee_id) REFERENCES employee (employeeid),
    FOREIGN KEY (album_id) REFERENCES album (albumid),
    FOREIGN KEY (customer_id) REFERENCES customer (customerid),
    FOREIGN KEY (store_id) REFERENCES store (storeid)
);



INSERT INTO artist (artistid, name, birth_year, birth_place, genre) VALUES(1, 'Curtis Mayfield', 1920, 'Chicago', 'Soul');
INSERT INTO artist (artistid, name, birth_year, birth_place, genre) VALUES(2, 'Isaac Hayes', 1942, 'Tennessee', 'Soul');
INSERT INTO artist (artistid, name, birth_year, birth_place, genre) VALUES(3, 'Ray Charles', 1930, 'Mississippi', 'Country and Soul');

INSERT INTO album (albumid, artist_id, title, published_year, comment) VALUES(1, 1, 'New World Order', 1990, 'the best ever!');
INSERT INTO album (albumid, artist_id, title, published_year, comment) VALUES(2, 1, 'Curtis', 1970, 'that man\'s got somthin\' to say');
INSERT INTO album (albumid, artist_id, title, published_year, comment) VALUES(3, 2, 'Shaft', 1972, 'he\'s the man');

INSERT INTO customer (customerid, first_name, last_name, email) VALUES(1, 'Jane', 'Doe', 'jane.doe@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(2, 'John', 'Doe', 'john.doe@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(3, 'Susan', 'Green', 'susan.green@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(4, 'Victoria', 'Alt', 'victory.alt@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(5, 'Will', 'Rippy', 'will.wippy@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(6, 'Tim', 'Raw', 'tim.raw@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(7, 'Nick', 'Fallow', 'nick.fallow@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(8, 'Ed', 'Burton', 'ed.burton@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(9, 'Jack', 'Woo', 'jack.woo@example.com');
INSERT INTO customer (customerid, first_name, last_name, email) VALUES(10, 'Maria', 'Gonzales', 'maria.gonzales@example.com');

INSERT INTO employee (employeeid, employeename) VALUES(1, 'Michael Jones');
INSERT INTO employee (employeeid, employeename) VALUES(2, 'Susi Weintraub');
INSERT INTO employee (employeeid, employeename) VALUES(3, 'Steve Hack');
INSERT INTO employee (employeeid, employeename) VALUES(4, 'Joan Kerr');
INSERT INTO employee (employeeid, employeename) VALUES(5, 'Marcus Roth');
INSERT INTO employee (employeeid, employeename) VALUES(6, 'Jack Mack');
INSERT INTO employee (employeeid, employeename) VALUES(7, 'Rita Doktor');
INSERT INTO employee (employeeid, employeename) VALUES(8, 'David Til');
INSERT INTO employee (employeeid, employeename) VALUES(9, 'Pia Eist');
INSERT INTO employee (employeeid, employeename) VALUES(10, 'Hanna Poll');
INSERT INTO employee (employeeid, employeename) VALUES(11, 'Jim Wells');
INSERT INTO employee (employeeid, employeename) VALUES(12, 'Sandra Wilson');

INSERT INTO store (storeid, manager, country, state, city, street, phone, building_xmldata) VALUES(1, 1, 'US', 'New York', 'New York', 'Broadway & 72nd Str', '123 456 7890', '<building><floors>4</floors><elevators>2</elevators><square_meters>3200</square_meters></building>');
INSERT INTO store (storeid, manager, country, state, city, street, phone, building_xmldata) VALUES(2, 2, 'US', 'New York', 'Larchmont', 'Palmer Ave 71', '456 7890', '<building><floors>2</floors><elevators>1</elevators><square_meters>400</square_meters></building>');

INSERT INTO department (departmentid, store_id, department_head, departmentname) VALUES(1, 1, 1, 'Sales');
INSERT INTO department (departmentid, store_id, department_head, departmentname) VALUES(2, 1, 4, 'Marketing');
INSERT INTO department (departmentid, store_id, department_head, departmentname) VALUES(3, 2, 7, 'Sales');
INSERT INTO department (departmentid, store_id, department_head, departmentname) VALUES(4, 2, 10, 'Marketing');

INSERT INTO employee_department (employee_id, department_id) VALUES(1, 1);
INSERT INTO employee_department (employee_id, department_id) VALUES(2, 1);
INSERT INTO employee_department (employee_id, department_id) VALUES(3, 1);
INSERT INTO employee_department (employee_id, department_id) VALUES(4, 2);
INSERT INTO employee_department (employee_id, department_id) VALUES(5, 2);
INSERT INTO employee_department (employee_id, department_id) VALUES(6, 2);
INSERT INTO employee_department (employee_id, department_id) VALUES(7, 3);
INSERT INTO employee_department (employee_id, department_id) VALUES(8, 3);
INSERT INTO employee_department (employee_id, department_id) VALUES(9, 3);
INSERT INTO employee_department (employee_id, department_id) VALUES(10, 4);
INSERT INTO employee_department (employee_id, department_id) VALUES(11, 4);
INSERT INTO employee_department (employee_id, department_id) VALUES(12, 4);

INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (1,  1,  1, 1, 1, '2005-05-25 16:32:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (2,  2,  1, 7, 2, '2005-06-05 12:56:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (3,  3,  1, 2, 1, '2005-07-10 11:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (4,  4,  1, 8, 2, '2005-07-10 10:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (5,  5,  1, 3, 1, '2005-07-10 13:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (6,  6,  1, 9, 2, '2005-07-10 14:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (7,  7,  1, 1, 1, '2005-07-10 15:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (8,  8,  1, 7, 2, '2005-07-10 16:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (9,  9,  1, 2, 1, '2005-07-10 18:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (10, 10, 1, 8, 2, '2005-07-10 19:03:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (11, 1,  2, 7, 2, '2005-05-25 16:23:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (12, 3,  2, 8, 2, '2005-07-10 11:56:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (13, 5,  2, 9, 2, '2005-07-10 13:12:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (14, 7,  2, 7, 2, '2005-07-10 15:09:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (15, 9,  2, 8, 2, '2005-07-10 18:49:00');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (16, 2,  3, 1, 1, '2005-06-05 12:56:12');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (17, 4,  3, 2, 1, '2005-07-10 10:03:32');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (18, 6,  3, 3, 1, '2005-07-10 14:03:52');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (19, 8,  3, 1, 1, '2005-07-10 16:03:01');
INSERT INTO sale (saleid, customer_id, album_id, employee_id, store_id, timestamp) VALUES (20, 10, 3, 2, 1, '2005-07-10 19:03:50');


