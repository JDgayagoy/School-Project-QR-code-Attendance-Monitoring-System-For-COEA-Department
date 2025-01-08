<?php
include 'cont.php';
session_start();

date_default_timezone_set('Asia/Manila');

function clearTempFolder() {
    $tempDir = '../images/temp/';
    if (is_dir($tempDir)) {
        $files = glob($tempDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $currentTime = date('H:i:s');
    $currentDate = date('Y-m-d');
    $attendanceSheets = [];

    $query = "SELECT s.*, c.course_code, sec.section 
              FROM students s
              LEFT JOIN courses c ON s.course_id = c.id
              LEFT JOIN sections sec ON s.section_id = sec.id
              WHERE s.student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    
    if ($student) {
        $settingsQuery = "SELECT table_name, time_in, time_out 
                          FROM attendance_settings 
                          WHERE date = CURDATE()";
        $settingsResult = $conn->query($settingsQuery);
        
        if ($settingsResult->num_rows > 0) {
            while ($settings = $settingsResult->fetch_assoc()) {
                $tableName = $settings['table_name'];
                $timeIn = strtotime($settings['time_in']);
                $timeOut = strtotime($settings['time_out']);
                $now = strtotime($currentTime);
                $gracePeriodIn = 30 * 60; 
                $gracePeriodOut = 30 * 60; 

                $checkQuery = "SELECT * FROM `$tableName` WHERE student_id = ? AND date = CURDATE()";
                $stmt = $conn->prepare($checkQuery);
                $stmt->bind_param("i", $student['id']);
                $stmt->execute();
                $attendance = $stmt->get_result()->fetch_assoc();
                
                if (!$attendance) {
                    if ($now >= ($timeIn - $gracePeriodIn) && $now <= ($timeIn + $gracePeriodIn)) {
                        $tempImage = $_POST['temp_image'];
                        $finalImage = 'images/time-in/' . $tempImage;
                        rename('../images/temp/' . $tempImage, '../' . $finalImage);

                        $insertQuery = "INSERT INTO `$tableName` 
                                       (student_id, last_name, first_name, middle_initial, 
                                        course_id, section_id, year, date, time_in, time_in_img, status) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, ?, 'Present')";
                        $stmt = $conn->prepare($insertQuery);
                        $stmt->bind_param("isssiisss", 
                            $student['id'],
                            $student['last_name'],
                            $student['first_name'],
                            $student['middle_initial'],
                            $student['course_id'],
                            $student['section_id'],
                            $student['year'],
                            $currentTime,
                            $finalImage
                        );
                        $stmt->execute();
                        $attendanceSheets[] = $tableName;
                        $_SESSION['message'] = "Attendance recorded successfully in $tableName.";
                    } else if ($now >= ($timeOut - $gracePeriodOut) && $now <= ($timeOut + $gracePeriodOut)) {
                        $tempImage = $_POST['temp_image'];
                        $finalImage = 'images/time-out/' . $tempImage;
                        rename('../images/temp/' . $tempImage, '../' . $finalImage);

                        $insertQuery = "INSERT INTO `$tableName` 
                                       (student_id, last_name, first_name, middle_initial, 
                                        course_id, section_id, year, date, time_out, time_out_img, status) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, ?, 'Present')";
                        $stmt = $conn->prepare($insertQuery);
                        $stmt->bind_param("isssiisss", 
                            $student['id'],
                            $student['last_name'],
                            $student['first_name'],
                            $student['middle_initial'],
                            $student['course_id'],
                            $student['section_id'],
                            $student['year'],
                            $currentTime,
                            $finalImage
                        );
                        $stmt->execute();
                        $attendanceSheets[] = $tableName;
                        $_SESSION['message'] = "Attendance recorded successfully in $tableName.";
                    } else {
                        $_SESSION['error'] = "You are not within the allowed time frame for attendance.";
                    }
                } else if ($attendance && !$attendance['time_out']) {
                    if ($now >= ($timeOut - $gracePeriodOut) && $now <= ($timeOut + $gracePeriodOut)) {
                        $tempImage = $_POST['temp_image'];
                        $finalImage = 'images/time-out/' . $tempImage;
                        rename('../images/temp/' . $tempImage, '../' . $finalImage);

                        $updateQuery = "UPDATE `$tableName` 
                                    SET time_out = ?, time_out_img = ?
                                    WHERE student_id = ? AND date = CURDATE()";
                        $stmt = $conn->prepare($updateQuery);
                        $stmt->bind_param("ssi", $currentTime, $finalImage, $student['id']);
                        $stmt->execute();
                        $_SESSION['message'] = "Time out recorded successfully in $tableName.";
                    } else {
                        $_SESSION['error'] = "It's not yet time to time out.";
                    }
                } else {
                    $_SESSION['error'] = "Attendance already recorded or not within the allowed time frame.";
                }
            }
        } else {
            $_SESSION['error'] = "No attendance found for today.";
        }
        
        clearTempFolder();
    } else {
        $_SESSION['error'] = "Student not found.";
    }
    
    $_SESSION['attendanceSheets'] = $attendanceSheets;
    header("Location: ../index.php");
    exit();
}
?>