<?php
include 'cont.php';

if(isset($_POST['submit'])) {
    $baseTableName = preg_replace('/[^A-Za-z0-9_]/', '', $_POST['table_name']);
    $currentDate = date('Ymd');
    $tableName = $baseTableName . '_' . $currentDate;
    
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
        $message = "Attendance Sheet for '{$tableName}' created successfully";
    } else {
        $message = "Error creating Attendance Sheet: " . $conn->error;
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
                <div class="mb-4 p-4 rounded <?php echo strpos($message, 'Error') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="table_name" class="block text-sm font-medium text-gray-700">Table Name</label>
                    <input type="text" name="table_name" id="table_name" required 
                           pattern="[A-Za-z0-9_]+" 
                           title="Only letters, numbers, and underscores allowed"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <button type="submit" name="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Table
                </button>
            </form>
        </div>
    </div>
</body>
</html>