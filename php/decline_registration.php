<?php
include 'cont.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decline'])) {
    $student_id = $_POST['student_id'];
    
    $deleteQuery = "DELETE FROM registration WHERE student_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $student_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration declined successfully.";
    } else {
        $_SESSION['error'] = "Error declining registration.";
    }
    
    header("Location: ../admin-registry.php");
    exit();
}
?>