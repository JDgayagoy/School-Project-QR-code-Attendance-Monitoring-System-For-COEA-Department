<?php
session_start();

if (isset($_SESSION['access_lvl']) && $_SESSION['access_lvl'] === 'Student') {
    header('Location: loginregister.php');
    exit();
}

include 'php/cont.php';

if(isset($_GET['course_id'])) {
    header('Content-Type: application/json');
    $course_id = $_GET['course_id'];
    
    $query = "SELECT * FROM sections WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sections = array();
    while($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
    
    echo json_encode($sections);
    exit();
}
function searchStudent($conn, $searchTerm) {
    $query = "SELECT s.*, c.course_code, sec.section 
              FROM students s
              JOIN courses c ON s.course_id = c.id
              JOIN sections sec ON s.section_id = sec.id
              WHERE s.student_id LIKE ? OR s.last_name LIKE ? OR s.first_name LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    return $stmt->get_result();
}

if (isset($_GET['search'])) {
    $searchResults = searchStudent($conn, $_GET['search']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .bg-accent-color{
            background-color: #F96D00;
        }
        .bg-second-color{
            background-color: #393E46;
        }
        .bg-primary-color{
            background-color:#222831;
        }
        .shadow-lg-white {
            box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.2), 
                        0 4px 6px -2px rgba(255, 255, 255, 0.2);
        }
        .inputBox::placeholder {
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            position: absolute;
            top: 50%; 
            left: 10px; 
            transform: translateY(-50%); 
        }
        .inputBox:focus::placeholder {
            font-size: 10px;
            top: 0;
            transform: translateY(0); 
        }
        .forgotBotton:hover{
            color: #F96D00;
            transition: 0.2s;
        }
        .navbar:hover{
            width: 280px;
            transition: 0.2s;
        }
        .navbot:hover{
            color: #F96D00;
            transition:0.4s;
        }
    </style>
</head>
<body>
    <main class="w-full max-h-max bg-gray-800 flex">
        <a href="php/logoutAction.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 absolute z-50 right-10 top-8">Logout</a>
        <section class="sticky t-0 l-0 b-0 h-screen w-24 z-10 bg-primary-color flex flex-col overflow-hidden whitespace-nowrap navbar">
            <ul class="flex flex-col ml-10 gap-6">
                <li>
                    <a href="#" class="flex items-center mt-8">
                        <img src="images/logo.png" alt="" width="50px">
                        <span class="ml-6 text-white text-xl font-bold">ADMIN</span>
                    </a>
                </li>
                <li class="mt-6"><a href="admin-homepage.php" class="text-white text-xl ml-3 navbot"><i class="fas fa-users"></i><span class="ml-9 text-sm">List of Students</span></a></li>
                <li><a href="admin-registry.php" class="text-white text-xl ml-4 navbot"><i class="fas fa-solid fa-check"></i><span class="ml-9 text-sm">Registry</span></a></li>
                <li><a href="admin-events.php" class="text-white text-xl ml-4 navbot"><i class="fas fa-solid fa-calendar"></i><span class="ml-10 text-sm">Events</span></a></li>
            </ul> 
        </section>
        <section class="w-full h-screen px-10 py-5 bg-primary-color flex flex-col">
            <div class="mt-3">
                <form action="" method="get" class="absolute">
                    <input type="text" name="search" placeholder="Search by Student ID..." class="rounded-lg px-5 w-96 h-12 relative left-0">
                    <input type="submit" value="Search" class="bg-blue-500 text-white px-4 py-2 rounded-lg ml-2">
                </form>
            </div>
            <div class="fixed bottom-5 right-5 z-50">
                <button onclick="openAddModal()" class="shadow-lg-white bg-indigo-600 text-white p-4 rounded-full hover:bg-indigo-700 transition-all duration-300 ease-in-out transform hover:translate-x-2">
                    <svg id="addIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span id="addText" class="hidden">Add Student</span>
                </button>
            </div>

            <script>
                const addButton = document.querySelector('.fixed button');
                const addIcon = document.getElementById('addIcon');
                const addText = document.getElementById('addText');

                addButton.addEventListener('mouseover', () => {
                    addButton.classList.add('px-6');
                    addIcon.classList.add('hidden');
                    addText.classList.remove('hidden');
                });

                addButton.addEventListener('mouseout', () => {
                    addButton.classList.remove('px-6');
                    addIcon.classList.remove('hidden');
                    addText.classList.add('hidden');
                });
            </script>

            <div class=" relative w-full h-auto bg-second-color rounded-lg top-20 p-3 z-0 pb-5">
                <h1 class=" text-3xl text-white font-bold ml-5 mt-4">List of Students</h1>
                <table class=" w-full divide-y divide-gray-200 rounded-tl-full rounded-tr-full mt-6">
                    <thead>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase">QR Code</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase">Student ID</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">Last Name</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">First Name</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">Middle Initial</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">Password</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">Course</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">Year</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">Section</th>
                        <th class="px-6 py-3 text-xs font-medium text-white uppercase ">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        include 'php/cont.php';

                        $results_per_page = 7;
                        $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                        if (!empty($search_query)) {
                            $query_total = "SELECT COUNT(*) AS total FROM students 
                                            WHERE student_id LIKE '%$search_query%' 
                                            OR first_name LIKE '%$search_query%' 
                                            OR last_name LIKE '%$search_query%'";
                        } else {
                            $query_total = "SELECT COUNT(*) AS total FROM students";
                        }

                        $result_total = mysqli_query($conn, $query_total);
                        $row_total = mysqli_fetch_assoc($result_total);
                        $total_records = $row_total['total'];
                        $total_pages = ceil($total_records / $results_per_page);

                        $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

                        $start_from = ($current_page - 1) * $results_per_page;
                        if (!empty($search_query)) {
                            $query = "SELECT s.*, c.course_code, sec.section 
                                    FROM students s 
                                    JOIN courses c ON s.course_id = c.id 
                                    JOIN sections sec ON s.section_id = sec.id
                                    WHERE student_id LIKE '%$search_query%' 
                                    OR first_name LIKE '%$search_query%' 
                                    OR last_name LIKE '%$search_query%'
                                    ORDER BY s.last_name ASC
                                    LIMIT $start_from, $results_per_page";
                        } else {
                            $query = "SELECT s.*, c.course_code, sec.section 
                                    FROM students s 
                                    JOIN courses c ON s.course_id = c.id 
                                    JOIN sections sec ON s.section_id = sec.id
                                    ORDER BY s.last_name ASC
                                    LIMIT $start_from, $results_per_page";
                        }

                        $result = mysqli_query($conn, $query);
                        ?>
                            <tbody class="">
                                <?php while ($student = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if ($student['image_path']): ?>
                                                <img src="<?php echo $student['image_path']; ?>" alt="QR Code" 
                                                     class="h-10 w-10 cursor-pointer mx-auto" 
                                                     onclick="openQRModal('<?php echo $student['image_path']; ?>')"/>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 text-white text-xs font-semibold whitespace-nowrap text-center">
                                            <?php echo $student['student_id']; ?>
                                        </td>
                                        <td class="px-1 text-white py-4 text-xs whitespace-nowrap">
                                            <?php echo $student['last_name']; ?>
                                        </td>
                                        <td class="px-1 text-white py-4 text-xs whitespace-nowrap">
                                            <?php echo $student['first_name']; ?>
                                        </td>
                                        <td class="px-1 text-white py-4 text-xs whitespace-nowrap text-center">
                                            <?php echo $student['middle_initial']; ?>
                                        </td>
                                        <td class="py-4 text-white text-center text-xs font-semibold">
                                            <?php echo $student['password']; ?>
                                        </td>
                                        <td class="px-2 text-white py-4 text-xs whitespace-nowrap text-center">
                                            <?php echo $student['course_code']; ?>
                                        </td>
                                        <td class="px-2 text-white py-4 text-xs whitespace-nowrap text-center">
                                            <?php echo $student['year']; ?>
                                        </td>
                                        <td class="px-2 text-white py-4 text-xs whitespace-nowrap text-center">
                                            <?php echo $student['section']; ?>
                                        </td>
                                        <td class="px-6 text-white py-4 text-xs font-semibold whitespace-nowrap text-center">
                                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 mr-3">Edit</button>
                                            <a href="php/Delete-student.php?id=<?php echo $student['id']; ?>" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-800" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div class="mt-6 text-center">
                            <?php
                            for ($page = 1; $page <= $total_pages; $page++) {
                                if (!empty($search_query)) {
                                    echo '<a href="admin-homepage.php?page=' . $page . '&search=' . $search_query . '" class="mx-1 px-3 py-2 rounded bg-indigo-600 text-white">' . $page . '</a>';
                                } else {
                                    echo '<a href="admin-homepage.php?page=' . $page . '" class="mx-1 px-3 py-2 rounded bg-indigo-600 text-white">' . $page . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <!-- Edit modal -->
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
                <form class="p-4 md:p-5" action="php/editAction.php" method="POST">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <input type="hidden" name="id" id="id">
                        <div class="col-span-2">
                            <label for="student_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student ID</label>
                                <input type="text" name="student_id" id="student_id" required placeholder="xx-xxxxx"
                        pattern="\d{2}-\d{5}" title="Student ID must be in the format xx-xxxxx and contain only numbers" maxlength="8"
                        oninput="if(this.value.length > 8) this.value = this.value.slice(0, 8);"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
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
                            <label for="course" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Course</label>
                            <select name="course" id="course" required onchange="updateSections()"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                <option value="">Select course</option>
                                <?php
                                $courses_query = "SELECT * FROM courses";
                                $courses_result = mysqli_query($conn, $courses_query);
                                while($course = mysqli_fetch_assoc($courses_result)): ?>
                                    <option value="<?php echo $course['id']; ?>">
                                        <?php echo $course['course_code'] . ' - ' . $course['course_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="section" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Section</label>
                            <select name="section" id="section" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                <!-- Section options will be populated based on the selected course -->
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year</label>
                            <input type="number" name="year" id="year"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                min="1" max="4" required>
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

    <!-- Add Student Modal -->
<div id="addModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-screen">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    <div class="relative p-4 w-full max-w-md z-50">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Student</h3>
                <button type="button" onclick="closeAddModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <form class="p-4 md:p-5" action="php/addAction.php" method="POST">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                     <label for="student_id" class="block text-gray-700 font-medium mb-1">Student ID:</label>
                    <input type="text" name="student_id" id="student_id" required placeholder="xx-xxxxx"
                        pattern="\d{2}-\d{5}" title="Student ID must be in the format xx-xxxxx and contain only numbers" maxlength="8"
                        oninput="if(this.value.length > 8) this.value = this.value.slice(0, 8);"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="add_last_name" class="block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                        <input type="text" name="add_last_name" id="add_last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="add_first_name" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                        <input type="text" name="add_first_name" id="add_first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="add_middle_initial" class="block mb-2 text-sm font-medium text-gray-900">Middle Initial</label>
                        <input type="text" name="add_middle_initial" id="add_middle_initial" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" maxlength="1" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="add_course" class="block mb-2 text-sm font-medium text-gray-900">Course</label>
                        <select name="add_course" id="add_course" required onchange="updateSectionsAddModal()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select course</option>
                            <?php
                                $courses_query = "SELECT * FROM courses";
                                $courses_result = mysqli_query($conn, $courses_query);
                                while($course = mysqli_fetch_assoc($courses_result)): ?>
                                    <option value="<?php echo $course['id']; ?>">
                                        <?php echo $course['course_code'] . ' - ' . $course['course_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-span-2">
                     <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input type="password" name="password" id="password" required placeholder=""
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="add_section" class="block mb-2 text-sm font-medium text-gray-900">Section</label>
                        <select name="add_section" id="add_section" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="year" class="block mb-2 text-sm font-medium text-gray-900">Year</label>
                        <input type="number" name="year" id="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" min="1" max="4" required>
                    </div>
                </div>
                <div class="flex justify-center">
                    <input type="submit" name="submit" value="Add Student" class="mt-4 w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
    <div class="relative bg-white rounded-lg p-8">
        <button onclick="closeQRModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalQRImage" src="" alt="QR Code" class="max-w-md mb-4">
        <div class="flex justify-center mt-4">
            <button onclick="downloadQR()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200">
                Download QR Code
            </button>
        </div>
    </div>
</div>

<script>
// Function to download the QR code image
function downloadQR() {
    const qrImage = document.getElementById('modalQRImage');
    const link = document.createElement('a');
    link.href = qrImage.src;
    link.download = 'QR Code';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function openQRModal(imagePath) {
    const modal = document.getElementById('qrModal');
    const modalImage = document.getElementById('modalQRImage');
    modalImage.src = imagePath;
    modal.classList.remove('hidden');
}

function closeQRModal() {
    const modal = document.getElementById('qrModal');
    modal.classList.add('hidden');
}

document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});

function updateSectionsAddModal() {
    const courseSelect = document.getElementById("add_course");
    const sectionSelect = document.getElementById("add_section");
    const selectedCourseId = courseSelect.value;
    
    sectionSelect.innerHTML = "";

    return fetch(`admin-homepage.php?course_id=${selectedCourseId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(section => {
                const option = document.createElement("option");
                option.value = section.id;
                option.text = section.section;
                sectionSelect.appendChild(option);
            });
        })
}

function updateSections(selectedSectionId = '') {
    const courseSelect = document.getElementById("course");
    const sectionSelect = document.getElementById("section");
    const selectedCourseId = courseSelect.value;

    sectionSelect.innerHTML = "";

    return fetch(`admin-homepage.php?course_id=${selectedCourseId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(section => {
                const option = document.createElement("option");
                option.value = section.id;
                option.text = section.section;
                if(section.id == selectedSectionId) {
                    option.selected = true;
                }
                sectionSelect.appendChild(option);
            });
        })
}

function openModal(id = '', studentId = '', lastName = '', firstName = '', middleInitial = '', courseId = '', year = '', sectionId = '') {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('id').value = id;
    document.getElementById('student_id').value = studentId;
    document.getElementById('last_name').value = lastName;
    document.getElementById('first_name').value = firstName;
    document.getElementById('middle_initial').value = middleInitial;
    document.getElementById('course').value = courseId;
    document.getElementById('year').value = year;


    updateSections(sectionId);
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}


document.addEventListener("DOMContentLoaded", updateSections);

 
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}
</script>
</body>

</html>
<script>
    document.getElementById('add_middle_initial').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });

    document.getElementById('add_first_name').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });

    document.getElementById('add_last_name').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });
    
    document.getElementById('middle_initial').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });

    document.getElementById('first_name').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });

    document.getElementById('last_name').addEventListener('input', function (e) {
        e.target.value = e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1);
    });
</script>
