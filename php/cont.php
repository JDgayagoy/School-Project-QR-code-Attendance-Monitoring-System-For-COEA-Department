<?php
  
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbName = "school";
  

  $conn = mysqli_connect("localhost","root","","school");

  if(mysqli_connect_error()) 
    echo "Failed to connect to MySQL: ". mysqli_connect_error();