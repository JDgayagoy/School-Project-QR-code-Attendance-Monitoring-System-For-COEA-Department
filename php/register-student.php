<?php
include("cont.php");
include('phpqrcode/qrlib.php');

$courses_query = "SELECT * FROM courses";
$courses_result = mysqli_query($conn, $courses_query);

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    $query = "SELECT * FROM sections WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sections = [];
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }

    echo json_encode($sections);
    exit();
}

if (isset($_POST["submit"])) {
    $student_id = $_POST["student_id"];
    $lname = $_POST["last_name"];
    $fname = $_POST["first_name"];
    $mname = substr($_POST["middle_initial"], 0, 1);
    $course_id = $_POST["course"];
    $year = $_POST["year"];
    $section_id = $_POST["section"];

    $stmt=$conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows==0){
        $qr_directory = "../QR-Codes/";
        if (!file_exists($qr_directory)) {
            mkdir($qr_directory, 0777, true);
        }
    
     
        $qr_file = $qr_directory . $student_id . ".png";
        QRcode::png($student_id, $qr_file, QR_ECLEVEL_L, 10);
    
       
        $sql = "INSERT INTO students (student_id, last_name, first_name, middle_initial, course_id, year, section_id, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        $image_path = "QR-Codes/" . $student_id . ".png";
        $stmt->bind_param("ssssiiis", $student_id, $lname, $fname, $mname, $course_id, $year, $section_id, $image_path);
        
        if($stmt->execute()){
            header("Location: .php\student-table.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }else{
        echo "Student already exist";
    }
 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body class="bg-gray-100 min-h-screen p-8">
    <form action="register-student.php" method="POST" class="max-w-sm mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Add New Student</h2>
        <div class="mb-3">
            <label for="student_id" class="block text-gray-700 font-medium mb-1">Student ID:</label>
            <input type="text" name="student_id" id="student_id" required placeholder="xx-xxxxx"
            pattern="\d{2}-\d{5}" title="Student ID must be in the format xx-xxxxx" maxlength="8"
            oninput="if(this.value.length > 8) this.value = this.value.slice(0, 8);"
            class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex space-x-4 mb-3">
            <div class="w-1/2">
                <label for="last_name" class="block text-gray-700 font-medium mb-1">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required
                    class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-1/2">
                <label for="first_name" class="block text-gray-700 font-medium mb-1">First Name:</label>
                <input type="text" name="first_name" id="first_name" required
                    class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex space-x-4 mb-3">
            <div class="w-1/2">
                <label for="middle_initial" class="block text-gray-700 font-medium mb-1">Middle Initial:</label>
                <input type="text" name="middle_initial" id="middle_initial" required
                    class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-1/2">
                <label for="year" class="block text-gray-700 font-medium mb-1">Year:</label>
                <input type="number" name="year" id="year" min="1" max="4" required
                    class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mb-3">
            <label for="course" class="block text-gray-700 font-medium mb-1">Course:</label>
            <select name="course" id="course" required onchange="updateSections()"
                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select course</option>
                <?php while($course = mysqli_fetch_assoc($courses_result)): ?>
                    <option value="<?php echo $course['id']; ?>">
                        <?php echo $course['course_code'] . ' - ' . $course['course_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="section" class="block text-gray-700 font-medium mb-1">Section:</label>
            <select name="section" id="section" required
                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <!-- Section options will be populated based on the selected course -->
            </select>
        </div>

        <div class="flex justify-center">
            <input type="submit" value="Add Student" name="submit"
                class="mt-4 w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        </div>
    </form>

    <script>
        function updateSections() {
            const courseSelect = document.getElementById("course");
            const sectionSelect = document.getElementById("section");
            const selectedCourseId = courseSelect.value;

            // Clear existing options
            sectionSelect.innerHTML = "";

            // Fetch sections for the selected course
            fetch(`register-student.php?course_id=${selectedCourseId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(section => {
                        const option = document.createElement("option");
                        option.value = section.id;
                        option.text = section.section;
                        sectionSelect.appendChild(option);
                    });
                });
        }

        // Initialize sections on page load
        document.addEventListener("DOMContentLoaded", updateSections);
    </script>
</body>

</html>
<script>
    document.getElementById('middle_initial').addEventListener('input', function (e) {
        e.target.value = e.target.value.toUpperCase();
    });

    document.getElementById('first_name').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });

    document.getElementById('last_name').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });
</script>