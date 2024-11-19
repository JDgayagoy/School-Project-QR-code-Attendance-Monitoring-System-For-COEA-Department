<?php 
include 'cont.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body>
    <h1 class="text-3xl font-bold text-center mt-5">Students</h1>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Middle Initial</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <?php

        $query = "SELECT id, last_name, first_name, middle_initial, course, year, section FROM students";
        $result = mysqli_query($conn, $query);

        $students = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }

        foreach ($students as $student) {
            echo "<tr>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['id']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['last_name']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['first_name']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['middle_initial']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['course']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['year']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['section']}</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>