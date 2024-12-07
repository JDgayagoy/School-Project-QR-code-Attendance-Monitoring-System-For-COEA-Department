<?php
include 'cont.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Generator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body>
    <h1 class="text-3xl font-bold text-center mt-5">Generate QR</h1>
    <form class="max-w-lg mx-auto p-4 bg-white shadow-lg rounded mt-20" action='generate-QR.php' method="get">
        <div class="mb-4">
            <label for="student_id" class="block text-gray-700 text-sm font-bold mb-2">Student ID</label>
            <input type="text" id="student_id" name="student_id" maxlength="8" minlength="8"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                required>
            <script>
            document.getElementById('student_id').addEventListener('input', function(e) {
                const value = e.target.value;
                if (value.length > 2 && value[2] !== '-') {
                    e.target.setCustomValidity('Student ID must be in the format XX-XXXX');
                    e.target.classList.add('border-red-500');
                } else {
                    e.target.setCustomValidity('');
                    e.target.classList.remove('border-red-500');

                }
            });
            </script>
        </div>
        <div class="mb-4">
            <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
            <input type="text" id="last_name" name="last_name" minlength="2" maxlength="50"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                required>
        </div>
        <div class="mb-4">
            <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
            <input type="text" id="first_name" minlength="2" maxlength="50" name="first_name"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                required>
        </div>
        <div class="mb-4">
            <label for="middle_initial" class="block text-gray-700 text-sm font-bold mb-2">Middle Initial</label>
            <input type="text" id="middle_initial" minlength="1" maxlength="1" name="middle_initial"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                required>
        </div>
        <div class="mb-4">
            <label for="course" class="block text-gray-700 text-sm font-bold mb-2">Course</label>
            <input type="text" id="course" name="course" minlength="2" maxlength="50"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                required>
        </div>
        <div class="mb-4">
            <label for="year" class="block text-gray-700 text-sm font-bold mb-2">Year</label>
            <input type="number" id="year" name="year" min="1" max="5"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                required>
        </div>
        <div class="mb-4">
            <label for="section" class="block text-gray-700 text-sm font-bold mb-2">Section</label>
            <input type="text" id="section" name="section" minlength="1" maxlength="10"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Generate</button>
        </div>
    </form>
</body>

</html>