<?php
include 'cont.php';  // Database connection

if (isset($_GET['id'])) {
    $name = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        header("Location: \QR-code-Attendance-Monitoring-System\php\student-table.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}else{
    header("Location: \QR-code-Attendance-Monitoring-System\php\student-table.php");
    exit();
}
?>
