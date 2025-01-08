<?php
  include("php/cont.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QR Code Scanner</title>
  <script src="./node_modules/html5-qrcode/html5-qrcode.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
  <style>
    .bg-accent-color {
      background-color: #F96D00;
    }
    .bg-second-color {
      background-color: #393E46;
    }
    .bg-primary-color {
      background-color: #222831;
    }
    .shadow-lg-white {
      box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.2), 
      0 4px 6px -2px rgba(255, 255, 255, 0.2);
    }
    #reader {
      width: 100%;
      max-width: 600px;
      height: 400px;
      margin: 0 auto;
    }
    .blur-background {
      filter: blur(8px);
      transition: filter 0.3s ease;
    }
    .visible {
      visibility: visible;
      opacity: 1;
      transition: opacity 0.3s ease;
    }
    .invisible {
      visibility: hidden;
      opacity: 0;
    }
  </style>
</head>
<body class="bg-primary-color text-white">
  <div id="content-wrapper">
    <h1 class="w-full text-center font-bold text-3xl mt-20">ATTENDANCE SCANNER</h1>
    <div class=" mt-20">
      <div id="reader"></div>
    </div>
  </div>

  <div id="formwindow" class="invisible transition absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/3 h-auto flex flex-col items-center justify-center z-50">
    <div class="flex flex-col items-center justify-center rounded-md bg-second-color w-full h-auto p-5 shadow-lg-white">
      <h1 class="text-xl font-bold mb-4 text-gray-300">Student Information</h1>
      
      <div id="student-info" class="w-full hidden">
        <div class="mb-4">
          <img id="captured-image" class="w-full h-48 object-cover rounded-lg mb-4" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-300 text-sm mb-1">Student ID:</label>
          <input type="text" id="student_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-2 text-white" readonly>
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-300 text-sm mb-1">Name:</label>
          <p id="student-name" class="bg-gray-700 border border-gray-600 rounded-lg p-2"></p>
        </div>

        <div class="mb-4">
          <label class="block text-gray-300 text-sm mb-1">Course:</label>
          <p id="student-course" class="bg-gray-700 border border-gray-600 rounded-lg p-2"></p>
        </div>

        <div class="mb-4">
          <label class="block text-gray-300 text-sm mb-1">Year:</label>
          <p id="student-year" class="bg-gray-700 border border-gray-600 rounded-lg p-2"></p>
        </div>

        <div class="mb-4">
          <label class="block text-gray-300 text-sm mb-1">Section:</label>
          <p id="student-section" class="bg-gray-700 border border-gray-600 rounded-lg p-2"></p>
        </div>

        <form id="attendanceForm" action="php/process_qr.php" method="post" class="flex flex-col gap-2">
          <input type="hidden" name="student_id" id="form_student_id">
          <input type="hidden" name="temp_image" id="temp_image">
          <button type="submit" id="submitBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg" disabled>Record Attendance</button>
          <button type="button" id="cancelbtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Cancel</button>
        </form>
      </div>

      <div id="not-found-message" class="hidden text-red-500 text-center">
        <p>Student not found in the database.</p>
        <button type="button" id="close-btn" class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Close</button>
      </div>
    </div>
  </div>
  <div id="cameraPromptModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 invisible">
    <div class="bg-second-color p-6 rounded-lg shadow-lg text-center text-white">
        <p class="text-lg mb-4 text-black">Please take a picture for validation</p>
        <button id="startCameraBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Start Camera</button>
        <button id="cancelCameraBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg ml-2">Cancel</button>
    </div>
  </div>

  <div id="cameraModal" class="invisible transition absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/3 h-auto flex flex-col items-center justify-center z-50">
    <div class="bg-second-color p-6 rounded-lg shadow-lg text-center text-white">
        <p class="text-lg mb-4 text-white">Taking picture in <span id="countdown">3</span> seconds...</p>
        <video id="webcam" autoplay class="mx-auto"></video>
        <canvas id="canvas" style="display:none;"></canvas>
    </div>
  </div>
  </div>
  <div id="messageModal" class="invisible transition absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/3 h-auto flex flex-col items-center justify-center z-50">
    <div class="flex flex-col items-center justify-center rounded-md bg-second-color w-full h-auto p-5 shadow-lg-white">
      <h1 class="text-xl font-bold mb-4">Message</h1>
      <p id="modalMessage" class="bg-gray-700 border border-gray-600 rounded-lg p-2 text-white"></p>
      <button id="closeModalBtn" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Close</button>
    </div>
  </div>
  <?php
    if (isset($_SESSION['message'])) {
      echo "<script>document.getElementById('modalMessage').textContent = '{$_SESSION['message']}';";
      echo "document.getElementById('messageModal').classList.remove('invisible');</script>";
      unset($_SESSION['message']);
    } elseif (isset($_SESSION['error'])) {
      echo "<script>document.getElementById('modalMessage').textContent = '{$_SESSION['error']}';";
      echo "document.getElementById('messageModal').classList.remove('invisible');</script>";
      unset($_SESSION['error']);
    }
  ?>
  <script>

  </script>

  <script>
  document.getElementById('student_id').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d-]/g, '');
    if (value.length > 2 && value[2] !== '-') {
      value = value.slice(0, 2) + '-' + value.slice(2);
    }
    if (value.length > 8) {
      value = value.slice(0, 8);
    }
    e.target.value = value;
    document.getElementById('form_student_id').value = value;
      
  
      fetch('php/fetch-student.php?student_id=' + value)
        .then(response => response.json())
        .then(data => {
          console.log('Fetched data:', data); 
          if (data.success) {
            document.getElementById('student-info').classList.remove('hidden');
            document.getElementById('not-found-message').classList.add('hidden');
            document.getElementById('student-name').textContent = `${data.last_name}, ${data.first_name} ${data.middle_initial}`;
            document.getElementById('student-course').textContent = `${data.course}`;
            document.getElementById('student-year').textContent = `${data.year}`;
            document.getElementById('student-section').textContent = `${data.section}`;
          } else {
            document.getElementById('student-info').classList.add('hidden');
            document.getElementById('not-found-message').classList.remove('hidden');
            console.error('Failed to fetch student info:', data);
          }
        })
        .catch(error => {
          console.error('Error fetching student info:', error);
          document.getElementById('student-info').classList.add('hidden');
        });
    }
  );
  </script>

  <script>
    let tempStudentData = null;
    let scannedQRCode = null;

    const scanner = new Html5QrcodeScanner('reader', {
      qrbox: {
        width: 250,
        height: 250,
      },
      fps: 20,
    });

    scanner.render(success, error);

    function success(result) {
        console.log("QR Code Scanned: ", result);
        scannedQRCode = result;
        
        fetch('php/fetch-student.php?student_id=' + result)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tempStudentData = data;
                    document.getElementById('cameraPromptModal').classList.remove('invisible');
                    scanner.pause();
                } else {
                    alert('Student not found');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function startCamera() {
        const video = document.getElementById('webcam');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                let countdown = 3;
                const countdownElement = document.getElementById('countdown');
                const countdownInterval = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;
                    if (countdown === 0) {
                        clearInterval(countdownInterval);
                        captureImage(video, stream);
                    }
                }, 1000);
            });
    }

    function captureImage(video, stream) {
        const canvas = document.getElementById('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        
        stream.getTracks().forEach(track => track.stop());

        const tempImageName = `${scannedQRCode}_${Date.now()}.png`;
        canvas.toBlob(blob => {
            const formData = new FormData();
            formData.append('image', blob, tempImageName);

            fetch('php/save_temp_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('temp_image').value = tempImageName;
                    document.getElementById('captured-image').src = `images/temp/${tempImageName}`;
                    document.getElementById('cameraModal').classList.add('invisible');
                    displayStudentInfo();
                }
            });
        });
    }

    function displayStudentInfo() {
        document.getElementById('student_id').value = scannedQRCode;
        document.getElementById('form_student_id').value = scannedQRCode;
        document.getElementById('student-name').textContent = 
            `${tempStudentData.last_name}, ${tempStudentData.first_name} ${tempStudentData.middle_initial}`;
        document.getElementById('student-course').textContent = tempStudentData.course;
        document.getElementById('student-year').textContent = tempStudentData.year;
        document.getElementById('student-section').textContent = tempStudentData.section;
        
        document.getElementById('student-info').classList.remove('hidden');
        document.getElementById('formwindow').classList.remove('invisible');
        document.getElementById('content-wrapper').classList.add('blur-background');
        document.getElementById('submitBtn').disabled = false;
    }

    function error(err) {
      console.error("QR Code Scanner Error: ", err);
    }

    document.getElementById("cancelbtn").addEventListener("click", function() {
      const form = document.getElementById("formwindow");
      form.classList.remove("visible");
      form.classList.add("invisible");

      document.getElementById("content-wrapper").classList.remove("blur-background");
    });

    document.getElementById("close-btn").addEventListener("click", function() {
      const form = document.getElementById("formwindow");
      form.classList.remove("visible");
      form.classList.add("invisible");

      document.getElementById("content-wrapper").classList.remove("blur-background");
    });

    document.getElementById("closeModalBtn").addEventListener("click", function() {
      document.getElementById("messageModal").classList.add("invisible");
    });

    document.getElementById('startCameraBtn').addEventListener('click', function() {
      document.getElementById('cameraPromptModal').classList.add('invisible');
      document.getElementById('cameraModal').classList.remove('invisible');
      startCamera();
    });

    document.getElementById('cancelCameraBtn').addEventListener('click', function() {
      document.getElementById('cameraPromptModal').classList.add('invisible');
      scanner.resume();
    });
  </script>
</body>
</html>