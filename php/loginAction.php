<?php
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
            echo "Login successful. Welcome, " . $student['first_name'] . "!";
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
