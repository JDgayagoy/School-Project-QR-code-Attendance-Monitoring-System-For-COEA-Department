<?php
include 'cont.php';

if(isset($_GET['id'])){
    $name = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("s", $name);  
    $stmt->execute();

    $result = $stmt->get_result();
    if($resultData = $result->fetch_assoc()){
        $lname = $resultData['last_name'];
        $fname = $resultData['first_name'];
        $mname = $resultData['middle_initial'];
    } else {
        echo "No student found with that first name.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="editAction.php">
        <input type="text" name="firstname" placeholder=<?php echo $fname?>>
        <input type="text" name="lastname" placeholder=<?php echo $lname?>>
        <input type="text" name="middlename" placeholder=<?php echo $mname?>>
        <input type="hidden" name="id" value=<?php echo $name?>>
        <input type="submit" value="update" name="update">
    </form>
</body>
</html>