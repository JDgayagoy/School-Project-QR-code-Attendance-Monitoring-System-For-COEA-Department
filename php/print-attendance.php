<?php
require('fpdf/fpdf.php');
include('cont.php');  // Assuming this is where your database connection is defined

// Create PDF object with 'L' for Landscape orientation
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add title
$pdf->Cell(0, 10, 'Attendance Sheet for trial_20250108', 0, 1, 'C');
$pdf->Ln(10);

// Set font for table headers
$pdf->SetFont('Arial', 'B', 12);

// Add table headers with adjusted sizes
$pdf->Cell(10, 8, 'ID', 1);
$pdf->Cell(23, 8, 'Student ID', 1);
$pdf->Cell(30, 8, 'Last Name', 1);
$pdf->Cell(30, 8, 'First Name', 1);
$pdf->Cell(10, 8, 'MI', 1);
$pdf->Cell(35, 8, 'Course Code', 1);
$pdf->Cell(20, 8, 'Section', 1);
$pdf->Cell(30, 8, 'Date', 1);
$pdf->Cell(30, 8, 'Time In', 1);
$pdf->Cell(30, 8, 'Time Out', 1);
$pdf->Cell(30, 8, 'Status', 1);
$pdf->Ln();

// Fetch data from the database with a JOIN
$query = "
    SELECT ka.id, ka.student_id, ka.last_name, ka.first_name, ka.middle_initial, 
           ka.date, ka.time_in, ka.time_out, ka.status, courses.course_code, 
           sections.section
    FROM trial_20250108 AS ka
    JOIN courses AS courses ON ka.course_id = courses.id
    JOIN sections AS sections ON ka.section_id = sections.id
    ORDER BY courses.course_code, sections.section
";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->get_result();

// Set font for table rows
$pdf->SetFont('Arial', '', 10);

// Loop through each row of data
$rowCount = 0;
while ($row = $results->fetch_assoc()) {
    // Add table data for each row
    $pdf->Cell(10, 8, $row['id'], 1);
    $pdf->Cell(23, 8, $row['student_id'], 1);
    $pdf->Cell(30, 8, $row['last_name'], 1);
    $pdf->Cell(30, 8, $row['first_name'], 1);
    $pdf->Cell(10, 8, $row['middle_initial'], 1);
    $pdf->Cell(35, 8, $row['course_code'], 1);
    $pdf->Cell(20, 8, $row['section'], 1);
    $pdf->Cell(30, 8, $row['date'], 1);
    $pdf->Cell(30, 8, $row['time_in'], 1);
    $pdf->Cell(30, 8, $row['time_out'], 1);
    $pdf->Cell(30, 8, $row['status'], 1);
    $pdf->Ln();
    
    // Optional: Check if rows exceed a certain count, and add a page break
    $rowCount++;
    if ($rowCount > 20) {  // Adjust number of rows per page as necessary
        $pdf->AddPage();  // Add a new page
        $rowCount = 0;  // Reset row count for new page
    }
}

// Output the PDF to the browser
$pdf->Output();
?>
