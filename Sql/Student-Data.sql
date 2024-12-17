CREATE DATABASE school;

USE school;

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(10) NOT NULL,
    course_name VARCHAR(255) NOT NULL
);

CREATE TABLE sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    section CHAR(1) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(10),
    last_name VARCHAR(50),
    first_name VARCHAR(50),
    middle_initial CHAR(1),
    year INT,
    course_id INT,
    section_id INT,
    image_path VARCHAR(100),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
);

INSERT INTO courses (course_code, course_name) VALUES
('BSABE', 'BACHELOR OF SCIENCE IN AGRICULTURAL AND BIOSYSTEMS ENGINEERING'),
('BSCHE', 'BACHELOR OF SCIENCE IN CHEMICAL ENGINEERING'),
('BSCPE', 'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING'),
('BSECE', 'BACHELOR OF SCIENCE IN ELECTRONICS ENGINEERING'),
('BSCE', 'BACHELOR OF SCIENCE IN CIVIL ENGINEERING'),
('BSEE', 'BACHELOR OF SCIENCE IN ELECTRICAL ENGINEERING'),
('BSGE', 'BACHELOR OF SCIENCE IN GEODETIC ENGINEERING'),
('BSA', 'BACHELOR OF SCIENCE IN ARCHITECTURE');

INSERT INTO sections (course_id, section) VALUES
(1, 'A'), (1, 'B'), (1, 'C'),
(2, 'A'), (2, 'B'),
(3, 'A'), (3, 'B'), (3, 'C'), (3, 'D'),
(4, 'A'), (4, 'B'), (4, 'C'),
(5, 'A'), (5, 'B'), (5, 'C'), (5, 'D'), (5, 'E'),
(6, 'A'), (6, 'B'), (6, 'C'),
(7, 'A'), (7, 'B'),
(8, 'A'), (8, 'B'), (8, 'C');

