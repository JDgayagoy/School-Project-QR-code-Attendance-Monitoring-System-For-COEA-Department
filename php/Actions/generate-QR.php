<?php
include '../Database/config.php';
require_once '../phpqrcode/qrlib.php';

$student_id = $_GET['student_id'];
$last_name = $_GET['last_name'];
$first_name = $_GET['first_name'];
$middle_initial = $_GET['middle_initial'];

$sql = "INSERT INTO students (id, last_name, first_name, middle_initial) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $student_id, $last_name, $first_name, $middle_initial);

if ($stmt->execute()) {
    $path = '../QR-codes/';
    $qrcode = $student_id . '.png';
    QRcode::png($student_id, $path . $qrcode, QR_ECLEVEL_L, 10);
} else {
    header("Location: ../QR-generator.php?error=failed_to_generate_qr_code");
    exit();
}

$stmt->close();
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
    <div class="flex items-center justify-center min-h-screen">
        <?php echo "<img src='$path$qrcode' alt='QR Code' class='w-100 h-100'>"; ?>
    </div>
</body>
</html>