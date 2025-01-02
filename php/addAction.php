<?php
include("cont.php");
include('phpqrcode/qrlib.php');

if (isset($_POST["submit"])) {
    $student_id = $_POST["student_id"];
    $lname = $_POST["add_last_name"];
    $fname = $_POST["add_first_name"];
    $mname = substr($_POST["add_middle_initial"], 0, 1);
    $pass = $_POST['password'];
    $course_id = $_POST["add_course"];
    $year = $_POST["year"];
    $section_id = $_POST["add_section"];

    $qr_directory = "../QR-Codes/";
    if (!file_exists($qr_directory)) {
        mkdir($qr_directory, 0777, true);
    }

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param('s', $student_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 0){
        $qr_filename = $student_id . ".png";
        $qr_path = $qr_directory . $qr_filename;
        $image_path = "QR-Codes/" . $qr_filename;
        
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        QRcode::png($student_id, $qr_path, QR_ECLEVEL_L, 10);
    
        $sql = "INSERT INTO students (student_id, last_name, first_name, middle_initial, password, course_id, year, section_id, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssiiis", $student_id, $lname, $fname, $mname, $hashed_password,$course_id, $year, $section_id, $image_path);
        
        if($stmt->execute()){
            header("Location: ../student-table.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }else{
        echo "Student already exist";
    }
}
?>