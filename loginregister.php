<?php
include 'php/cont.php';

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // User is logged in
    if (isset($_SESSION['access_lvl']) && $_SESSION['access_lvl'] === 'Admin') {
        header('Location: admin-homepage.php');
        exit();
    }else{
        header('Location: student-homepage.php');
        exit();
    }
} else {
}


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
    <title>Welcome </title>
    <link rel="stylesheet" type="text/css" href="landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <input type="checkbox" id="flip">
        <div class="cover">
            <div class="front">
                <img src="images/coea-blur.jpg" alt="">
                <div class="text">
                    <span class="text-1">SwiftScan: QR Code-Enabled<br> Attendance Monitoring System </span>
                    <span class="text-2"> Cagayan State University - Carig Campus </span>
                </div>
            </div>
            <div class="back">
                <img class="backImg" src="images/csu-blur.jpg" alt="">
                <div class="text">
                    <span class="text-1">Welcome to SwiftScan!</span>
                    <span class="text-2">Let's get you started!</span>
                </div>
            </div>
        </div>
        <div class="forms">
            <div class="form-content">
                <div class="login-form">
                    <div class="title">Welcome back!</div>
                    <form action="php/loginAction.php" method="post">
                        <div class="input-boxes">
                            <div class="input-box">
                                <i class="fas fa-user"></i>
                                <input name="student_id" type="text" placeholder="Enter your ID number" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input name="password" type="password" placeholder="Enter your password" required>
                            </div>
                            <div class="text"><a href="#">Forgot password?</a></div>
                            <div class="button input-box">
                                <input type="submit" value="Log In">
                            </div>
                            <div class="text sign-up-text">Don't have an account? <label for="flip">Register now</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="signup-form">
                    <div class="title">Registration</div>
                    <form action="php/registerAction.php" method="post">
                        <div class="input-boxes">
                            <div class="form-row">
                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="student_id" placeholder="ID No." pattern="\d{2}-\d{5}" title="Student ID must be in the format xx-xxxxx" required>
                                </div>
                                <div class="input-box">
                                    <i class="fas fa-address-card"></i>
                                    <input type="number" name="year" placeholder="Year" min="1" max="4" required>
                                </div>
                                <div class="input-box">
                                <i class="fas fa-file-signature"></i>
                                    <input type="text" name="middlen" placeholder="M.I" maxlength="1" required>
                                </div>
                            </div>
                            <div class="form-row">
                            <div class="input-box">
                                    <i class="fas fa-file-signature"></i>
                                    <input type="text" name="firstn" placeholder="First Name" pattern="[A-Za-z\s]+" title="Please enter only letters and spaces" required>
                                    </div>
                                <div class="input-box">
                                    <i class="fas fa-file-signature"></i>
                                    <input type="text" name="lastn" placeholder="Last Name" pattern="[A-Za-z\s]+" title="Please enter only letters and spaces" required>
                                </div>
                            </div>
                            <div class="form-row-special">
                                <select name="course" id="course" class="dropdown" required onchange="updateSections()">
                                    <option value="" disabled selected hidden>-- Select Course --</option>
                                    <?php while($course = mysqli_fetch_assoc($courses_result)): ?>
                                        <option value="<?php echo $course['id']; ?>">
                                            <?php echo $course['course_code'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="input-box"></div>
                                <select name="section" id="section" class="dropdown" required>
                                    <option value="" disabled selected hidden>-- Select Section --</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="input-box">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" name="password" placeholder="Enter password" minlength="6" required>
                                </div>
                                <div class="input-box">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" name="confirm_password" placeholder="Confirm Password" minlength="6" required>
                                </div>
                            </div>
                            <div id="error_message" class="text-red-500 font-bold mt-2" style="display: none;"></div> <!-- Error message -->
                            <div class="button input-box">
                                <input name="submit" type="submit" value="Sign Up" onclick="return validatePassword()">
                            </div>
                        </div>
                    </form>
                    <div class="text">Already have an account? <label for="flip">Login now</label></div>
                </div>
                <script>
                    function updateSections() {
                        const courseSelect = document.getElementById("course");
                        const sectionSelect = document.getElementById("section");
                        const selectedCourseId = courseSelect.value;

                        sectionSelect.innerHTML = '<option value="" disabled selected hidden>-- Select Section --</option>';
                        
                        fetch(`loginregister.php?course_id=${selectedCourseId}`)
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

                    document.addEventListener("DOMContentLoaded", () => {
                        // Ensure sections get populated when page is loaded if course is preselected
                        if (document.getElementById("course").value) {
                            updateSections();
                        }
                    });
                </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>