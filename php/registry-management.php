<?php
include 'cont.php';
include('phpqrcode/qrlib.php');


$sql = "SELECT r.*, c.course_code, s.section 
        FROM registration r
        LEFT JOIN courses c ON r.course_id = c.id
        LEFT JOIN sections s ON r.section_id = s.id";
$result = $conn->query($sql);

if(!$result) {
    $error = "Error fetching registrations: " . $conn->error;
}

if(isset($_POST['accept'])) {
    $id = $_POST['id'];
    

    $query = "SELECT * FROM registration WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    
    if($student) {

        $qr_directory = "../QR-Codes/";
        if (!file_exists($qr_directory)) {
            mkdir($qr_directory, 0777, true);
        }


        $qr_filename = $student['student_id'] . ".png";
        $qr_path = $qr_directory . $qr_filename;
        $image_path = "QR-Codes/" . $qr_filename;
        
   
        QRcode::png($student['student_id'], $qr_path, QR_ECLEVEL_L, 10);
        

        $hashedPassword = password_hash($student['password'], PASSWORD_DEFAULT);

        $insert = "INSERT INTO students (student_id, last_name, first_name, middle_initial, password, year, course_id, section_id, image_path) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("sssssiiss", 
            $student['student_id'],
            $student['last_name'],
            $student['first_name'],
            $student['middle_initial'],
            $hashedPassword,
            $student['year'],
            $student['course_id'],
            $student['section_id'],
            $image_path
        );
        
        if($stmt->execute()) {

            $delete = "DELETE FROM registration WHERE id = ?";
            $stmt = $conn->prepare($delete);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
         
            $result = $conn->query($sql);
            $message = "Student accepted successfully and QR code generated";
        } else {
            $error = "Error accepting student: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Registration Management</h1>
        
        <?php if(isset($message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo $row['student_id']; ?></td>
                        <td class="px-6 py-4">
                            <?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_initial']; ?>
                        </td>
                        <td class="px-6 py-4"><?php echo $row['course_code']; ?></td>
                        <td class="px-6 py-4"><?php echo $row['section']; ?></td>
                        <td class="px-6 py-4"><?php echo $row['year']; ?></td>
                        <td class="px-6 py-4">
                            <form method="POST" class="inline-flex space-x-2">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="accept" 
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    Accept
                                </button>
                                <button type="submit" name="decline" 
                                        onclick="return confirm('Are you sure you want to decline this registration?')"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Decline
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>