-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 06:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_settings`
--

CREATE TABLE `attendance_settings` (
  `table_name` varchar(100) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_settings`
--

INSERT INTO `attendance_settings` (`table_name`, `time_in`, `time_out`, `date`) VALUES
('trial_20250108', '00:58:00', '13:58:00', '2025-01-08');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `course_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`) VALUES
(1, 'BSABE', 'BACHELOR OF SCIENCE IN AGRICULTURAL AND BIOSYSTEMS ENGINEERING'),
(2, 'BSCHE', 'BACHELOR OF SCIENCE IN CHEMICAL ENGINEERING'),
(3, 'BSCPE', 'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING'),
(4, 'BSECE', 'BACHELOR OF SCIENCE IN ELECTRONICS ENGINEERING'),
(5, 'BSCE', 'BACHELOR OF SCIENCE IN CIVIL ENGINEERING'),
(6, 'BSEE', 'BACHELOR OF SCIENCE IN ELECTRICAL ENGINEERING'),
(7, 'BSGE', 'BACHELOR OF SCIENCE IN GEODETIC ENGINEERING'),
(8, 'BSA', 'BACHELOR OF SCIENCE IN ARCHITECTURE');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `section` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `course_id`, `section`) VALUES
(1, 1, 'A'),
(2, 1, 'B'),
(3, 1, 'C'),
(4, 2, 'A'),
(5, 2, 'B'),
(6, 3, 'A'),
(7, 3, 'B'),
(8, 3, 'C'),
(9, 3, 'D'),
(10, 4, 'A'),
(11, 4, 'B'),
(12, 4, 'C'),
(13, 5, 'A'),
(14, 5, 'B'),
(15, 5, 'C'),
(16, 5, 'D'),
(17, 5, 'E'),
(18, 6, 'A'),
(19, 6, 'B'),
(20, 6, 'C'),
(21, 7, 'A'),
(22, 7, 'B'),
(23, 8, 'A'),
(24, 8, 'B'),
(25, 8, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `image_path` varchar(100) DEFAULT NULL,
  `access_lvl` varchar(20) DEFAULT 'Student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `last_name`, `first_name`, `middle_initial`, `password`, `year`, `course_id`, `section_id`, `image_path`, `access_lvl`) VALUES
(25, '23-31289', 'Roberts', 'Jan Kester', 'I', '$2y$10$SnVs5rzb4nYEJP5/n9AxMOWkIZN87VpthQPbdRm/X1sC3YPoCDuei', 4, 3, 6, 'QR-Codes/23-31289.png', 'Student'),
(26, '00-00000', 'Admin', 'Admin', 'A', '$2y$10$7mIkjGHzG.yjyKsWpdkXG.fa55NcS7omMgE2S.9JQooROpzQ6sLk6', 1, 8, 23, 'QR-Codes/00-00000.png', 'Admin'),
(27, '22-22237', 'Gayagoy', 'John David', 'D', '$2y$10$fYh4DFKb8cpHfQ4d920xRujQYXQukUG2LVDf8QY3Sxb2bdWn2sSFS', 3, 2, 5, 'QR-Codes/22-22237.png', 'Student'),
(28, '49-12391', 'Doming', 'Go', 'S', '$2y$10$mkHjBATFZYd5ohZtX.44vexYYbpjy6yD8h9oGjEN0JIi1DjNEVNlG', 2, 8, 24, 'QR-Codes/49-12391.png', 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `trial_20250108`
--

CREATE TABLE `trial_20250108` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('Present','Late','Absent') DEFAULT 'Absent'
) ;

--
-- Dumping data for table `trial_20250108`
--

INSERT INTO `trial_20250108` (`id`, `student_id`, `last_name`, `first_name`, `middle_initial`, `course_id`, `section_id`, `date`, `time_in`, `time_out`, `status`) VALUES
(1, '26', 'Admin', 'Admin', 'A', 8, 23, '2025-01-08', '00:58:35', NULL, 'Present'),
(2, '27', 'Gayagoy', 'John David', 'D', 2, 5, '2025-01-08', '01:01:17', NULL, 'Present'),
(3, '28', 'Doming', 'Go', 'S', 8, 24, '2025-01-08', '01:02:36', NULL, 'Present');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_settings`
--
ALTER TABLE `attendance_settings`
  ADD PRIMARY KEY (`table_name`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `trial_20250108`
--
ALTER TABLE `trial_20250108`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course_id` (`course_id`),
  ADD KEY `idx_section_id` (`section_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `trial_20250108`
--
ALTER TABLE `trial_20250108`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);

--
-- Constraints for table `trial_20250108`
--
ALTER TABLE `trial_20250108`
  ADD CONSTRAINT `fk_trial_20250108_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trial_20250108_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
