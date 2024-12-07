<!DOCTYPE html>
<?php include 'cont.php';?>
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <?php
        $query = "SELECT * FROM students";
        $result = mysqli_query($conn, $query);

        while ($student = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['id']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['last_name']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['first_name']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['middle_initial']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['course']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['year']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['section']}</td>";
            // Using id to delete the student
            echo "<td><a href='Delete-student.php?id={$student['id']}' class='text-red-500 hover:text-red-700' onclick='return confirm(\"Are you sure you want to delete this student?\");'>Delete</a></td>";
            echo "<td><a href='Edit-Student.php?id={$student['id']}' class='text-blue-500 hover:text-blue-700';'>Edit</a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>
