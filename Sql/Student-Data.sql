CREATE DATABASE school;

USE school;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(10),
    last_name VARCHAR(50),
    first_name VARCHAR(50),
    middle_initial CHAR(1),
    course VARCHAR(10),
    year INT,
    section CHAR(1)
);

ALTER TABLE students
ADD COLUMN image_path VARCHAR(100);