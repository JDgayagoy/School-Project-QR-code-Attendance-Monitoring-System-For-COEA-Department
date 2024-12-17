<?php
    include 'cont.php';

    if(isset($_POST['update'])){
        $id = $_POST['id'];
        $student_id = $_POST['student_id'];
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $mname = $_POST['middle_initial'];
        $course_id = $_POST['course'];
        $year = $_POST['year'];
        $section_id = $_POST['section'];

        // Use prepared statement to prevent SQL injection
        $query = "UPDATE students SET 
            student_id = ?, 
            first_name = ?, 
            last_name = ?, 
            middle_initial = ?, 
            course_id = ?, 
            year = ?, 
            section_id = ? 
            WHERE id = ?";
            
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssiiis", $student_id, $fname, $lname, $mname, $course_id, $year, $section_id, $id);
        
        if($stmt->execute()){
            header("Location: student-table.php");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "No update request received.";
    }
?>