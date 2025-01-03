<?php
include 'cont.php';
header('Content-Type: application/json');

if(isset($_POST['student_id']) && isset($_POST['table_name'])) {
    $student_id = $_POST['student_id'];
    $table_name = $_POST['table_name'];

  
    $check_query = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($student = $result->fetch_assoc()) {
       
        $check_attendance = "SELECT * FROM `$table_name` 
                           WHERE student_id = ? AND date = CURDATE()";
        $check_stmt = $conn->prepare($check_attendance);
        $check_stmt->bind_param("i", $student['id']);
        $check_stmt->execute();
        $attendance_result = $check_stmt->get_result();

        if($attendance_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Student already checked in today']);
            exit();
        }

     
        $insert_query = "INSERT INTO `$table_name` 
            (student_id, last_name, first_name, middle_initial, course_id, section_id, date, time_in, status) 
            VALUES (?, ?, ?, ?, ?, ?, CURDATE(), CURTIME(), 'Present')";
        
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param(
            "isssii", 
            $student['id'],
            $student['last_name'],
            $student['first_name'],
            $student['middle_initial'],
            $student['course_id'],
            $student['section_id']
        );

        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Student added successfully']);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error adding student: ' . $stmt->error
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>