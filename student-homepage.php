<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
include 'php/cont.php';

// Modified SQL query to join courses and sections tables to get course_code and section
$sql = "
    SELECT students.*, courses.course_code, sections.section 
    FROM students 
    JOIN courses ON students.course_id = courses.id 
    JOIN sections ON students.section_id = sections.id
    WHERE students.student_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <link rel="stylesheet" type="text/css" href="student.css"/>
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
  </head>
  <body>
    <div class="profile-card">
      <div class="image">
        <img src="<?php echo $row['image_path']?>" alt="" class="profile-img" />
      </div>

      <div class="text-data">
        <span class="name"><?php echo($row['last_name']. ", " . $row['first_name'])?></span>
        <span class="deets"><?php echo $row['course_code'] . ", " . $row['section']; ?> </span>
      </div>

    <section>
      <button class="show-modal" onclick="downloadQR()">Download QR code</button>
      <span class="overlay"></span>
      <div class="modal-box">
        <i class="fa-regular fa-circle-check"></i>
        <h2>Completed</h2>
        <h3>You have successfully downloaded your QR code!</h3>
        <div class="buttons">
          <button class="close-btn">Ok, Close</button>
        </div>
      </div>
    </section>
    <a class="">Logout</a>
  </div>
    <script>
      const section = document.querySelector("section"),
        overlay = document.querySelector(".overlay"),
        showBtn = document.querySelector(".show-modal"),
        closeBtn = document.querySelector(".close-btn");
      showBtn.addEventListener("click", () => section.classList.add("active"));
      overlay.addEventListener("click", () =>
        section.classList.remove("active")
      );
      closeBtn.addEventListener("click", () =>
        section.classList.remove("active")
      );

      function downloadQR() {
            const qrImage = document.getElementById('modalQRImage');
            const link = document.createElement('a');
            link.href = qrImage.src;
            link.download = 'QR Code';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
  </body>
</html>