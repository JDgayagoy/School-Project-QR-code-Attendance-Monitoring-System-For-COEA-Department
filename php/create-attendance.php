<?php
date_default_timezone_set('Asia/Manila');
include 'cont.php';

if(isset($_POST['submit'])) {
    $baseTableName = str_replace(' ', '_', $_POST['table_name']);
    $baseTableName = preg_replace('/[^A-Za-z0-9_]/', '', $baseTableName);
    $currentDate = date('Ymd');
    $tableName = $baseTableName . '_' . $currentDate;
    $timeIn = $_POST['time_in'];
    $timeOut = $_POST['time_out'];
    $date = date('Y-m-d');
    
    // Create attendance_settings table if not exists
    $settingsSQL = "CREATE TABLE IF NOT EXISTS attendance_settings (
        table_name VARCHAR(100) PRIMARY KEY,
        time_in TIME NOT NULL,
        time_out TIME NOT NULL,
        date DATE NOT NULL
    )";
    $conn->query($settingsSQL);
    
    // Check if the attendance sheet already exists
    $checkQuery = "SELECT * FROM attendance_settings WHERE table_name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $tableName);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $message = "Attendance Sheet '{$tableName}' already exists.";
    } else {
        // Insert new attendance settings
        $insertSettingsSQL = "INSERT INTO attendance_settings (table_name, time_in, time_out, date) 
                              VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSettingsSQL);
        $stmt->bind_param("ssss", $tableName, $timeIn, $timeOut, $date);
        $stmt->execute();
        
        // Create attendance table if not exists
        $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id VARCHAR(10),
            last_name VARCHAR(50),
            first_name VARCHAR(50),
            middle_initial CHAR(1),
            course_id INT,
            section_id INT,
            year INT,
            date DATE NOT NULL,
            time_in TIME,
            time_in_img VARCHAR(255),
            time_out TIME,
            time_out_img VARCHAR(255),
            status ENUM('Present', 'Late', 'Absent') DEFAULT 'Absent',
            INDEX idx_course_id (course_id),
            INDEX idx_section_id (section_id),
            CONSTRAINT `fk_{$tableName}_course` 
            FOREIGN KEY (course_id) REFERENCES courses(id) 
            ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk_{$tableName}_section` 
            FOREIGN KEY (section_id) REFERENCES sections(id) 
            ON DELETE CASCADE ON UPDATE CASCADE,
            CHECK (time_out IS NULL OR time_in IS NULL OR time_out > time_in)
        ) ENGINE=InnoDB";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: ../admin-events.php");
            exit();
        } else {
            $message = "Error creating Attendance Sheet: " . $conn->error;
        }
    }
}
?>
