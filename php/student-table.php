<!DOCTYPE html>
<?php include 'cont.php';

// Initialize variables with empty values
$id = $student_id = $lname = $fname = $mname = $course = $year = $section = $image_path = '';

if(isset($_GET['id'])){
    $name = $_GET['id'];

    // Use prepared statement to handle any special characters like spaces
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("s", $name);  // "s" indicates the parameter is a string
    $stmt->execute();

    $result = $stmt->get_result();
    if($resultData = $result->fetch_assoc()){
        $id = $resultData['id'];
        $student_id = $resultData['student_id'];
        $lname = $resultData['last_name'];
        $fname = $resultData['first_name'];
        $mname = $resultData['middle_initial'];
        $course = $resultData['course']; 
        $year = $resultData['year'];
        $section = $resultData['section'];
        $image_path = $resultData['image_path'];
    } else {
        echo "No student found with that ID.";
    }

    $stmt->close();
}
?>
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Middle
                    Initial</th>
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
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['student_id']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['last_name']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['first_name']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['middle_initial']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['course']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['year']}</td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'>{$student['section']}</td>";
            echo "<td><a href='Delete-student.php?id={$student['id']}' class='text-red-500 hover:text-red-700' onclick='return confirm(\"Are you sure you want to delete this student?\");'>Delete</a></td>";
            echo "<td class='px-6 py-4 whitespace-nowrap'><button onclick=\"openModal('" . addslashes($student['id']) . "', '" . addslashes($student['student_id']) . "', '" . addslashes($student['last_name']) . "', '" . addslashes($student['first_name']) . "', '" . addslashes($student['middle_initial']) . "', '" . addslashes($student['course']) . "', '" . addslashes($student['year']) . "', '" . addslashes($student['section']) . "')\" class='text-indigo-600 hover:text-indigo-900'>Edit</button></td>";
            echo "</tr>";

                 }
        ?>
        </tbody>
    </table>

    <!-- Main modal -->
    <div id="editModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-screen">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

        <div class="relative p-4 w-full max-w-md z-50">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Update Student
                    </h3>
                    <button type="button" onclick="closeModal()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form class="p-4 md:p-5" action="editAction.php" method="POST">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <input type="hidden" name="id" id="id">
                        <div class="col-span-2">
                            <label for="student_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student ID</label>
                            <input type="text" name="student_id" id="student_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                maxlength="10" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="last_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                            <input type="text" name="last_name" id="last_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                maxlength="50" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="first_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                            <input type="text" name="first_name" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                maxlength="50" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="middle_initial"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Middle Initial</label>
                            <input type="text" name="middle_initial" id="middle_initial"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                maxlength="1" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="course"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Course</label>
                            <input type="text" name="course" id="course"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                maxlength="10" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="year"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year</label>
                            <input type="number" name="year" id="year"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                min="1" max="4" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="section"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Section</label>
                            <input type="text" name="section" id="section"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                maxlength="1" required>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <input type="submit" value="Update" name="update"
                            class="mt-4 w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openModal(id = '', studentId = '', lastName = '', firstName = '', middleInitial = '', course = '', year = '',
        section = '') {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('id').value = id;
        document.getElementById('student_id').value = studentId;
        document.getElementById('last_name').value = lastName;
        document.getElementById('first_name').value = firstName;
        document.getElementById('middle_initial').value = middleInitial;
        document.getElementById('course').value = course;
        document.getElementById('year').value = year;
        document.getElementById('section').value = section;
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    </script>
</body>

</html>