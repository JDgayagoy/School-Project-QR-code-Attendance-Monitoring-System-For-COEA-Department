<?php
    include 'cont.php';

    if(isset($_POST['update'])){
        $id = $_POST['id'];
        $student_id = $_POST['student_id'];
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $mname = $_POST['middle_initial'];
        $course = $_POST['course'];
        $year = $_POST['year'];
        $section = $_POST['section'];

        $query = "UPDATE students SET `student_id` = '$student_id', `first_name` = '$fname', `last_name` = '$lname', `middle_initial` = '$mname', `course` = '$course', `year` = '$year', `section` = '$section' WHERE `id` = '$id'";
        $result = mysqli_query($conn, $query);

        if($result){
            header("Location: student-table.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "No update request received.";
    }
?>