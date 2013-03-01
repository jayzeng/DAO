DROP DATABASE IF EXISTS dbUnitTest;

CREATE DATABASE IF NOT EXISTS dbUnitTest;

use dbUnitTest;

DROP TABLE IF EXISTS Employee;

CREATE TABLE IF NOT EXISTS Employee (
        employee_id INT NOT NULL AUTO_INCREMENT,
        first_name VARCHAR(100) NOT NULL ,
        last_name  VARCHAR(100) NOT NULL,
        PRIMARY KEY (employee_id)
        ) ENGINE=InnoDB;
