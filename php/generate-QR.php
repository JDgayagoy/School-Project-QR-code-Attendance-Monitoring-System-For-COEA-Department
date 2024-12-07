<?php
// Include the database connection file (adjust path as necessary)
include 'cont.php';  // Adjust path if needed

require_once '../php/phpqrcode/qrlib.php';  // Adjusted path// Ensure the path is correct

// Check if the required parameters are passed through the URL
if (isset($_GET['student_id'], $_GET['last_name'], $_GET['first_name'], $_GET['middle_initial'], $_GET['course'], $_GET['year'], $_GET['section'])) {
    // Sanitize and assign the GET parameters
    $student_id = $_GET['student_id'];
    $last_name = $_GET['last_name'];
    $first_name = urldecode($_GET['first_name']);  // Decode in case of spaces or special characters
    $middle_initial = $_GET['middle_initial'];
    $course = $_GET['course'];
    $year = $_GET['year'];
    $section = $_GET['section'];
} else {
    // Handle the case where some parameters are missing
    echo "Required parameters are missing.";
    exit();
}

// Prepare SQL statement to insert student details into the database
$sql = "INSERT INTO students (id, last_name, first_name, middle_initial, course, year, section) VALUES (?, ?, ?, ?, ?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    // Bind parameters to the prepared statement
    $stmt->bind_param("sssssis", $student_id, $last_name, $first_name, $middle_initial, $course, $year, $section);

    // Execute the statement and check if successful
    if ($stmt->execute()) {
        // Ensure the directory for QR codes exists
        $path = '../QR-codes/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);  // Create the directory if it doesn't exist
        }

        // Generate the QR code
        $qrcode = $student_id . '.png';
        QRcode::png($student_id, $path . $qrcode, QR_ECLEVEL_L, 10);

        // Output the QR code image in HTML
        echo "<div class='flex items-center justify-center min-h-screen'>
                <img src='$path$qrcode' alt='QR Code' class='w-100 h-100'>
              </div>";

    } else {
        // Error in SQL execution
        header("Location: ../QR-generator.php?error=failed_to_generate_qr_code");
        exit();
    }

    // Close the statement
    $stmt->close();
} else {
    // Error preparing the SQL statement
    header("Location: ../QR-generator.php?error=failed_to_prepare_statement");
    exit();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR-Code</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body>
    <!-- QR code image will be displayed above using PHP echo -->
</body>
</html>
