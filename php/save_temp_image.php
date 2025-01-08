<?php
header('Content-Type: application/json');

if (isset($_FILES['image'])) {
    $tempDir = '../images/temp/';
    $tempFile = $tempDir . basename($_FILES['image']['name']);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $tempFile)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save temp image']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image uploaded']);
}
?>