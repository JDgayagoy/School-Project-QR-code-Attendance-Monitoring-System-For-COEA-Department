<?php
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
            student_id INT,
            last_name VARCHAR(50),
            first_name VARCHAR(50),
            middle_initial CHAR(1),
            course_id INT,
            section_id INT,
            date DATE NOT NULL,
            time_in TIME,
            time_out TIME,
            status ENUM('Present', 'Late', 'Absent') DEFAULT 'Absent',
            INDEX idx_student_id (student_id),
            INDEX idx_course_id (course_id),
            INDEX idx_section_id (section_id),
            CONSTRAINT `fk_{$tableName}_student` 
                FOREIGN KEY (student_id) REFERENCES students(id) 
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk_{$tableName}_course` 
                FOREIGN KEY (course_id) REFERENCES courses(id) 
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk_{$tableName}_section` 
                FOREIGN KEY (section_id) REFERENCES sections(id) 
                ON DELETE CASCADE ON UPDATE CASCADE,
            CHECK (time_out IS NULL OR time_in IS NULL OR time_out > time_in)
        ) ENGINE=InnoDB";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Attendance Sheet '{$tableName}' created successfully.";
        } else {
            $message = "Error creating Attendance Sheet: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Attendance Sheet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Create Attendance Sheet</h2>
            
            <?php if(isset($message)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form action="create-attendance.php" method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="table_name">Attendance Sheet Name</label>
                    <input type="text" name="table_name" id="table_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="time_in">Time In</label>
                    <input type="time" name="time_in" id="time_in" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="time_out">Time Out</label>
                    <input type="time" name="time_out" id="time_out" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>