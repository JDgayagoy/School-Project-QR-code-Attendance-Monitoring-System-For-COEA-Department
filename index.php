<?php
  include("php/cont.php"); // Ensure the database connection file is correct
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
    <div id="reader"></div>
  </div>

  <div id="formwindow" class="invisible transition absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/5 h-1/5 flex flex-col items-center justify-center z-50">
    <form action="php/process_qr.php" method="post" class="flex flex-col items-center justify-center rounded-md bg-second-color w-auto h-auto p-5 shadow-lg-white">
      <h1 class=" text-xl font-bold ">Confirm ID</h1>
      <input type="text" name="student_id" id="student_id" class="mt-2 w-full h-11 bg-transparent border border-white rounded-lg text-white p-5">
      <button id="submitbot" type="submit" class="bg-accent-color text-white w-2/3 h-10 mt-3 rounded-xl font-bold">SUBMIT</button>
      <button type="button" id="cancelbtn" class="bg-orange-900 text-white w-2/3 h-10 mt-3 rounded-xl font-bold">CANCEL</button>
    </form>
  </div>

  <script>
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
      alert(`Scanned QR Code: ${result}`);
      document.getElementById("student_id").value = result;

      const form = document.getElementById("formwindow");
      form.classList.remove("invisible");
      form.classList.add("visible");

      document.getElementById("content-wrapper").classList.add("blur-background");
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
  </script>
</body>
</html>
