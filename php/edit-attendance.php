<?php
include 'cont.php';

if (isset($_POST['edit_submit'])) {
    $originalTableName = $_POST['original_table_name'];
    $baseTableName = $_POST['edit_table_name'];
    $timeIn = $_POST['edit_time_in'];
    $timeOut = $_POST['edit_time_out'];


    $dateSuffix = substr($originalTableName, -9);
    $newTableName = str_replace(' ', '_', $baseTableName) . $dateSuffix;


    $conn->begin_transaction();

    try {

        $updateQuery = "UPDATE attendance_settings SET table_name = ?, time_in = ?, time_out = ? WHERE table_name = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssss", $newTableName, $timeIn, $timeOut, $originalTableName);
        $stmt->execute();

        $renameTableQuery = "ALTER TABLE `$originalTableName` RENAME TO `$newTableName`";
        $conn->query($renameTableQuery);

        $conn->commit();

        header("Location: ../admin-events.php");
        exit();
    } catch (mysqli_sql_exception $exception) {

        $conn->rollback();
        throw $exception;
    }
}
?>