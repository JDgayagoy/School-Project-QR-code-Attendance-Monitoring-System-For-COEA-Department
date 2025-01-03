<?php
include 'php/cont.php';

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/output.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <style>
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
        #course::placeholder{
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
    </style>
</head>
<body>
    <main class="w-screen h-screen flex px-10 relative bg-primary-color">
        <section class="absolute left-0 top-1/3 w-1/3 h-40 bg-second-color rounded-tr-full rounded-br-full flex p-4 shadow-lg-white items-center">
            <div id="orange" class="ml-4 h-3/4 w-3 bg-accent-color"></div>
            <div class="ml-3 w-auto h-full flex flex-col py-3">
                <h1 class=" text-3xl text-white font-bold italic ">Cagayan State University <span class=" font-normal">- Carig Campus</span></h1>
                <h2 class="text-white">Attendance monitoring system</h2>
            </div>
        </section>
        <section class="absolute right-20 w-auto h-auto flex flex-col items-center top-20">
            <img src="images/logo.png" alt="" width='120px'>
            <h1 class=" text-5xl font-bold text-white">REGISTER</h1>
            <form action="php/registerAction.php" method="POST" class="flex flex-col gap-5 mt-8 justify-center items-center">
                <div class="w-auto h-auto flex gap-7">
                    <input type="text" required placeholder="Student ID" name="student_id"
                    class=" w-52 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                    <input type="number" required placeholder="Year" name="year" min='1' max='5'
                    class=" w-52 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                    <input type="text" required placeholder="M.I" name="middlen"
                    class=" w-52 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                    <!-- Section options will be populated based on the selected course -->
                    </select>
                </div>
                <div class="w-auto h-auto flex gap-10">
                    <input type="text" required placeholder="First Name" name="firstn"
                    class=" w-80 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                    <input type="text" required placeholder="Last Name" name="lastn"
                    class=" w-80 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                </div>
                <div class="w-auto h-auto flex justify-around">
                    <select name="course" id="course" required onchange="updateSections()" placeholder="Select Course"
                        class=" w-3/4 h-14 py-1 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                        <option value="">Select course</option>
                        <?php while($course = mysqli_fetch_assoc($courses_result)): ?>
                            <option value="<?php echo $course['id']; ?>" class="text-white bg-second-color">
                                <?php echo $course['course_code'] . ' - ' . $course['course_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <select name="section" id="section" required placeholder="Section"
                        class=" w-20 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                    </select>
                </div>
                <div class="w-auto h-auto flex gap-11 justify-content">
                    <input type="password" id="password" required placeholder="Password" name="password"
                    class="w-80 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                    
                    <input type="password" id="confirm_password" required placeholder="Confirm Password"
                    class="w-80 h-14 bg-transparent border-b-2 border-white inputBox px-5 text-white">
                </div>

                <div id="error_message" class="text-red-500 font-bold mt-2" style="display: none;"></div> <!-- Error message -->
                <input name="submit" type="submit" value="SUBMIT" class="bg-accent-color rounded-full text-lg font-bold text-white w-48 h-14" onclick="return validatePassword()">
            </form>
        </section>
    </main>
    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorMessage = document.getElementById('error_message');
            if (password !== confirmPassword) {
                errorMessage.textContent = "Passwords do not match!";
                errorMessage.style.display = "block";
                return false;
            }
            errorMessage.style.display = "none";
            return true;
        }


        function updateSections() {
            const courseSelect = document.getElementById("course");
            const sectionSelect = document.getElementById("section");
            const selectedCourseId = courseSelect.value;

            sectionSelect.innerHTML = "";
            fetch(`register.php?course_id=${selectedCourseId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(section => {
                        const option = document.createElement("option");
                        option.value = section.id;
                        option.text = section.section;
                        option.classList.add("text-white", "bg-second-color")
                        sectionSelect.appendChild(option);
                    });
                });
        }
        document.addEventListener("DOMContentLoaded", updateSections);
    </script>
</body>
</html>
