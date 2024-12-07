<?php
    include 'cont.php';

    if(isset($_POST['update'])){
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $mname = $_POST['middlename'];
        $id = $_POST['id']; 
        $query = "UPDATE students SET `first_name` = '$fname', `last_name` = '$lname', `middle_initial` = '$mname' WHERE `id` = '$id'";
        $result = mysqli_query($conn, $query);

        if($result == TRUE){
            header("Location: student-table.php");
            exit();
        }
    }
?>