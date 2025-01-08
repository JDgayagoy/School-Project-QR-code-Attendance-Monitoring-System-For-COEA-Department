<?php
include 'php/cont.php';
include('php/phpqrcode/qrlib.php');

session_start();

if (isset($_SESSION['access_lvl']) && $_SESSION['access_lvl'] === 'Student') {
    header('Location: login.php');
    exit();
}


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

        $qr_directory = "QR-Codes/";
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
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .bg-accent-color{
            background-color: #F96D00;
        }
        .bg-second-color{
            background-color: #393E46;
        }
        .bg-primary-color{
            background-color:#222831;
        }
        .shadow-lg-white {
            box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.2), 
                        0 4px 6px -2px rgba(255, 255, 255, 0.2);
        }
        .inputBox::placeholder {
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            position: absolute;
            top: 50%; 
            left: 10px; 
            transform: translateY(-50%); 
        }
        .inputBox:focus::placeholder {
            font-size: 10px;
            top: 0;
            transform: translateY(0); 
        }
        .forgotBotton:hover{
            color: #F96D00;
            transition: 0.2s;
        }
        .navbar:hover{
            width: 280px;
            transition: 0.2s;
        }
        .navbot:hover{
            color: #F96D00;
            transition:0.4s;
        }
    </style>
</head>
    <body>
        <main class="w-full h-screen bg-gray-800 flex">
        <a href="php/logoutAction.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 absolute z-50 right-10 top-8">Logout</a>
            <section class="sticky t-0 l-0 b-0 h-screen w-24 z-10 bg-primary-color flex flex-col overflow-hidden whitespace-nowrap navbar">
                <ul class="flex flex-col ml-10 gap-6">
                    <li>
                        <a href="#" class="flex items-center mt-8">
                            <img src="images/logo.png" alt="" width="50px">
                            <span class="ml-6 text-white text-xl font-bold">ADMIN</span>
                        </a>
                    </li>
                    <li class="mt-6"><a href="admin-homepage.php" class="text-white text-xl ml-3 navbot"><i class="fas fa-users"></i><span class="ml-9 text-sm">List of Students</span></a></li>
                    <li><a href="admin-registry.php" class="text-white text-xl ml-4 navbot"><i class="fas fa-solid fa-check"></i><span class="ml-9 text-sm">Registry</span></a></li>
                    <li><a href="admin-events.php" class="text-white text-xl ml-4 navbot"><i class="fas fa-solid fa-calendar"></i><span class="ml-10 text-sm">Events</span></a></li>
                    <li><a href="admin-attendance.php" class="text-white text-xl ml-4 navbot"><i class="fas fa-clipboard-check"></i><span class="ml-10 text-sm">Attendance</span></a></li>
                </ul> 
            </section>
            <section class="w-full h-screen px-10 py-5 bg-primary-color flex flex-col">
                <div class="mt-3">
                    <form action="" class="absolute">
                        <input type="submit" value=""><i class="fas fa-search absolute right-2 z-10 top-1/3"></i></input>
                        <input type="text" placeholder="Search..." class=" rounded-lg px-5 w-96 h-12 relative left-0">
                    </form>
                </div>
                <div class=" relative w-full h-auto bg-second-color rounded-lg top-20 p-3">
                    <h1 class=" text-3xl text-white font-bold ml-5 mt-4">Registry Management</h1>
                    <table class=" w-full divide-y divide-gray-200 rounded-tl-full rounded-tr-full mt-6">
                        <thead class="">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">Student ID</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white  uppercase">Name</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white  uppercase">Course</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white  uppercase">Section</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white  uppercase">Year</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white  uppercase">Actions</th>
                            </tr>
                        </thead> 
                        <tbody class=" divide-y divide-gray-200">
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6  text-white py-4 text-xs whitespace-nowrap text-center"><?php echo $row['student_id']; ?></td>
                                <td class="px-6  text-white py-4 text-xs whitespace-nowrap text-center">
                                    <?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_initial']; ?>
                                </td>
                                <td class="px-6  text-white py-4 text-xs whitespace-nowrap text-center"><?php echo $row['course_code']; ?></td>
                                <td class="px-6  text-white py-4 text-xs whitespace-nowrap text-center"><?php echo $row['section']; ?></td>
                                <td class="px-6  text-white py-4 text-xs whitespace-nowrap text-center"><?php echo $row['year']; ?></td>
                                <td class="px-6 py-4 flex justify-center">
                                    <form method="POST" class="inline-flex space-x-2">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="accept" 
                                                class="bg-green-500 text-white text-xs px-3 py-1 rounded hover:bg-green-600">
                                            Accept
                                        </button>
                                        <button type="submit" name="decline" 
                                                onclick="return confirm('Are you sure you want to decline this registration?')"
                                                class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600">
                                            Decline
                                        <?php
                                        if(isset($_POST['decline'])) {
                                            $id = $_POST['id'];
                                            $delete = "DELETE FROM registration WHERE id = ?";
                                            $stmt = $conn->prepare($delete);
                                            $stmt->bind_param("i", $id);
                                            if($stmt->execute()) {
                                                $result = $conn->query($sql);
                                                $message = "Registration declined successfully";
                                            } else {
                                                $error = "Error declining registration: " . $stmt->error;
                                            }
                                        }
                                        ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </body>
</html>