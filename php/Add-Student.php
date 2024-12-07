<?php include("cont.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="Lname" id="Lname">
        <input type="text" name="Fname" id="Fname">
        <input type="text" name="Mname" id="Mname">
        <input type="submit" name="submit">
    </form>
    <?php
    if(isset($_POST["submit"])){
        $lname = $_POST["Lname"];
        $Fname = $_POST["Fname"];
        $Mname = $_POST["Mname"];
        $sql = "INSERT INTO students (last_name, first_name, middle_initial) VALUES ('$lname','$Fname', '$Mname')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>
</body>
</html>