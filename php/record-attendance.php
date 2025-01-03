<?php
session_start();
include 'cont.php';

if (!isset($_SESSION['logged_in']) || !isset($_GET['table']) || !isset($_GET['action'])) {
    header("Location: ../student-homepage.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$table_name = $_GET['table'];
$action = $_GET['action'];
$currentTime = date('H:i:s');

try {
   
    $studentQuery = "SELECT s.*, c.course_code, sec.section 
                    FROM students s
                    LEFT JOIN courses c ON s.course_id = c.id
                    LEFT JOIN sections sec ON s.section_id = sec.id
                    WHERE s.student_id = ?";
    $stmt = $conn->prepare($studentQuery);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

  
    $checkQuery = "SELECT * FROM `$table_name` WHERE student_id = ? AND date = CURDATE()";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $student['id']);
    $stmt->execute();
    $attendance = $stmt->get_result()->fetch_assoc();

    if (!$attendance) {
    
        $insertQuery = "INSERT INTO `$table_name` 
                       (student_id, last_name, first_name, middle_initial, 
                        course_id, section_id, date, time_in, status) 
                       VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?, 'Present')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("isssiis", 
            $student['id'],
            $student['last_name'],
            $student['first_name'],
            $student['middle_initial'],
            $student['course_id'],
            $student['section_id'],
            $currentTime
        );
        $stmt->execute();
    } else if ($action == 'time_out' && !$attendance['time_out']) {
     
        $updateQuery = "UPDATE `$table_name` 
                       SET time_out = ? 
                       WHERE student_id = ? AND date = CURDATE()";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $currentTime, $student['id']);
        $stmt->execute();
    }

    header("Location: student-homepage.php");
    exit();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>