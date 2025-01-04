<?php
session_start();
include "cont.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc(); 

        if (password_verify($password, $student['password'])) {
            if($student['access_lvl'] == "Admin"){
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['access_lvl'] = $student['access_lvl'];
                $_SESSION['logged_in'] = true;
                header("Location: ../admin-homepage.php");
                exit();
            }else{
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['access_lvl'] = $student['access_lvl'];
                $_SESSION['logged_in'] = true;
                header("Location: ../student-homepage.php");
                exit();
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No student found with the provided ID.";
    }

    $stmt->close();
    $conn->close();
}
?>