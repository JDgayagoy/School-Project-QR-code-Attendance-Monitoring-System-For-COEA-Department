<?php
require('fpdf/fpdf.php');
include('cont.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Attendance Sheet for ' . $table_name, 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(20, 8, 'ID', 1);
    $pdf->Cell(30, 8, 'Last Name', 1);
    $pdf->Cell(30, 8, 'First Name', 1);
    $pdf->Cell(10, 8, 'MI', 1);
    $pdf->Cell(15, 8, 'Year', 1);
    $pdf->Cell(35, 8, 'Course Code', 1);
    $pdf->Cell(20, 8, 'Section', 1);
    $pdf->Cell(30, 8, 'Date', 1);
    $pdf->Cell(30, 8, 'Time In', 1);
    $pdf->Cell(30, 8, 'Time Out', 1);
    $pdf->Cell(30, 8, 'Status', 1);
    $pdf->Ln();

    $query = "
        SELECT ka.id, ka.student_id, ka.last_name, ka.first_name, ka.middle_initial, 
            ka.date, ka.time_in, ka.time_out, ka.status, ka.year, courses.course_code, 
            sections.section
        FROM $table_name AS ka
        JOIN courses AS courses ON ka.course_id = courses.id
        JOIN sections AS sections ON ka.section_id = sections.id
        ORDER BY courses.course_code, ka.year ASC, sections.section";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->get_result();

    $pdf->SetFont('Arial', '', 10);

    $rowCount = 0;
    while ($row = $results->fetch_assoc()) {
        $pdf->Cell(20, 8, $row['student_id'], 1);
        $pdf->Cell(30, 8, $row['last_name'], 1);
        $pdf->Cell(30, 8, $row['first_name'], 1);
        $pdf->Cell(10, 8, $row['middle_initial'], 1);
        $pdf->Cell(15, 8, $row['year'], 1);
        $pdf->Cell(35, 8, $row['course_code'], 1);
        $pdf->Cell(20, 8, $row['section'], 1);
        $pdf->Cell(30, 8, $row['date'], 1);
        $pdf->Cell(30, 8, $row['time_in'], 1);
        $pdf->Cell(30, 8, $row['time_out'], 1);
        $pdf->Cell(30, 8, $row['status'], 1);
        $pdf->Ln();

        $rowCount++;
        if ($rowCount > 20) {
            $pdf->AddPage();
            $rowCount = 0;
        }
    }
    $pdf->Output();
    exit();
} else {
    die("Table name is not set.");
}
?>
