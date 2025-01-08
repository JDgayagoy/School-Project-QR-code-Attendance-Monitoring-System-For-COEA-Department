<?php
    include 'cont.php';
    error_reporting(E_ALL);
ini_set('display_errors', 1);


    if (isset($_POST['submit'])) {
        // Get form data
        $student_id = $_POST['student_id'];
        $year = $_POST['year'];
        $middlen = $_POST['middlen'];
        $firstn = $_POST['firstn'];
        $lastn = $_POST['lastn'];
        $course = $_POST['course'];
        $section = $_POST['section'];
        $password = $_POST['password'];

        $sql = "INSERT INTO registration (student_id, last_name, first_name, middle_initial, password, course_id, year, section_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssii", $student_id, $lastn, $firstn, $middlen, $password, $course, $year, $section);

        if ($stmt->execute()) {
            header("Location: ../loginregister.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
?>
