<?php
  include("php/cont.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="./node_modules/html5-qrcode/html5-qrcode.min.js"></script>
</head>
<body>
  <div class=" w-500 h-500" id="reader">reader</div>
  <script>

    const scanner = new Html5QrcodeScanner('reader',{
      qrbox:{
        width:500,
        height:500,
      },
      fps:20,
    });

    scanner.render(success, error);
    function success(result){
      console.log(result);
      scanner.clear();
      
    }
    function error(err){
      console.error(err);
    }
  </script>
</body>
</html>