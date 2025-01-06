<?php
include 'cont.php';

header('Content-Type: application/json');

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    
    $query = "SELECT s.*, c.course_code, sec.section 
              FROM students s
              LEFT JOIN courses c ON s.course_id = c.id
              LEFT JOIN sections sec ON s.section_id = sec.id
              WHERE s.student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'last_name' => $student['last_name'],
            'first_name' => $student['first_name'],
            'middle_initial' => $student['middle_initial'],
            'course' => $student['course_code'],
            'year' => $student['year'],
            'section' => $student['section']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No student ID provided']);
}
?>