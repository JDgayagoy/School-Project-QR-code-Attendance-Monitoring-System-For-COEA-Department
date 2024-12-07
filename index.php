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
  <style>
    /* Styling the reader container */
    #reader {
      width: 100%;
      max-width: 600px; /* Make sure the video feed is responsive */
      height: 400px; /* Set a fixed height */
      margin: auto; /* Center the reader */
    }
  </style>
</head>
<body>
  <div id="reader"></div> <!-- The QR code scanner will display here -->

  <script>
    const scanner = new Html5QrcodeScanner('reader', {
      qrbox: {
        width: 250,  // Set the size of the QR code box
        height: 250, // Maintain the aspect ratio of the QR box
      },
      fps: 20, // Set frames per second (adjust if necessary)
    });

    scanner.render(success, error);

    function success(result) {
      // Log the scanned result
      console.log("QR Code Scanned: ", result);
      
      // You can also display the result in the UI or handle further actions
      alert(`Scanned QR Code: ${result}`);

      // Clear the scanner after a successful scan
      scanner.clear();
    }

    function error(err) {
      console.error("QR Code Scanner Error: ", err);
    }
  </script>
</body>
</html>
