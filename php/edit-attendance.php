<?php
include 'cont.php';

if (isset($_POST['edit_submit'])) {
    $tableName = $_POST['edit_table_name'];
    $timeIn = $_POST['edit_time_in'];
    $timeOut = $_POST['edit_time_out'];

    $updateQuery = "UPDATE attendance_settings SET time_in = ?, time_out = ? WHERE table_name = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $timeIn, $timeOut, $tableName);
    $stmt->execute();

    header("Location: ../admin-events.php");
    exit();
}
?>