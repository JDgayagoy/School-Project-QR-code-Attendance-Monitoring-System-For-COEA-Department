<?php
include 'cont.php';

if(isset($_GET['table'])) {
    $tableName = $_GET['table'];

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $records_per_page = 10;
    $offset = ($page - 1) * $records_per_page;
    
    $total_query = "SELECT COUNT(*) as count FROM `$tableName`";
    $total_result = $conn->query($total_query);
    $total_records = $total_result->fetch_assoc()['count'];
    $total_pages = ceil($total_records / $records_per_page);
    
    $sql = "SELECT a.*, s.student_id as student_number, 
            c.course_code, sec.section
            FROM `$tableName` a
            LEFT JOIN students s ON a.student_id = s.id
            LEFT JOIN courses c ON a.course_id = c.id
            LEFT JOIN sections sec ON a.section_id = sec.id
            ORDER BY a.year, c.course_code, sec.section, s.last_name
            LIMIT $offset, $records_per_page";
            
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <style>
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
    </style>
</head>
<body class="bg-primary-color">
<a href="../admin-events.php" class=" absolute top-5 left-10 py-4 px-5 bg-accent-color text-white rounded-lg"><i class="fas fa-arrow-left" aria-hidden="true"></i></a>
    <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white ">Attendance Records</h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-10">
            <table class="min-w-full divide-y bg-second-color">
                <thead class="bg-second-color">
                    <tr>
                        <th class="px-6 py-5 text-left text-xs font-medium text-white uppercase">Student ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Last Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">First Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">MI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Section</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Time In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Time In Img</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Time Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Time Out Img</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class=" divide-y divide-gray-200">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['student_id']; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['last_name']; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['first_name']; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['middle_initial']; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['course_code']; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['section']; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['year']; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase"><?php echo $row['time_in'] ? date('h:i A', strtotime($row['time_in'])) : '-'; ?></td>
                        <td class="px-6 py-2 text-center text-xs font-medium text-white uppercase">
                            <?php if($row['time_in_img']): ?>
                                <img src="../<?php echo $row['time_in_img']; ?>" alt="Time In Image" width="50" 
                                     onclick="openTimeInModal('../<?php echo $row['time_in_img']; ?>')" 
                                     class="cursor-pointer">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4"><?php echo $row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : '-'; ?></td>
                        <td class="px-6 py-4">
                            <?php if($row['time_out_img']): ?>
                                <img src="../<?php echo $row['time_out_img']; ?>" alt="Time Out Image" width="50"
                                     onclick="openTimeOutModal('../<?php echo $row['time_out_img']; ?>')"
                                     class="cursor-pointer">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo $row['status'] == 'Present' ? 'bg-green-100 text-green-800' : 
                                    ($row['status'] == 'Late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?table=<?php echo $tableName; ?>&page=<?php echo $i; ?>" class="px-3 py-1 bg-accent-color text-white rounded"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
    <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between mb-4">
        <button onclick="openAddModal()" class="bg-accent-color hover:bg-orange-500 text-white font-bold py-2 px-4 rounded">
            Add Student
        </button>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add Student to Attendance</h3>
                <form id="addStudentForm" class="mt-4">
                    <input type="hidden" name="table_name" value="<?php echo $tableName; ?>">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="student_id">
                            Student ID
                        </label>
                        <input type="text" name="student_id" id="student_id" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex justify-between mt-4">
                        <button type="button" onclick="closeAddModal()" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- time in image modal -->
<div id="timeInModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Time In Image</h3>
            <img id="timeInImage" src="" alt="Time In Image" class="mt-4">
            <button onclick="closeTimeInModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                Close
            </button>
        </div>
    </div>
</div>
<!-- time out image modal -->
<div id="timeOutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Time Out Image</h3>
            <img id="timeOutImage" src="" alt="Time Out Image" class="mt-4">
            <button onclick="closeTimeOutModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                Close
            </button>
        </div>


    <script>
        function openAddModal() {
            document.getElementById('addStudentModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addStudentModal').classList.add('hidden');
        }

        document.getElementById('addStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('add-attendance.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        });

        function openTimeInModal(imagePath) {
            document.getElementById('timeInImage').src = imagePath;
            document.getElementById('timeInModal').classList.remove('hidden');
        }
        function closeTimeInModal() {
            document.getElementById('timeInModal').classList.add('hidden');
        }
    </script>
</body>
</html>