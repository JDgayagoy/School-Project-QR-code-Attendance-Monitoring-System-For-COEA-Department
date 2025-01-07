<?php
include 'php/cont.php';

$query = "SELECT * FROM attendance_settings";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->get_result();
if(isset($_POST['drop_table'])) {
    $tableName = $_POST['table_name'];
    $hold = $tableName;

    // Delete from attendance_settings
    $sql = "DELETE FROM attendance_settings WHERE table_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $hold);
    $stmt->execute();

    if ($tableName === 'attendance_settings') {
        $sql = "DELETE FROM `attendance_settings` WHERE table_name = '$tableName'";
        if($conn->query($sql)) {
            $message = "Attendance settings for table '$tableName' deleted successfully";
        } else {
            $error = "Error deleting attendance settings: " . $conn->error;
        }
    } else {
        // Drop the actual table
        $sql = "DROP TABLE IF EXISTS `$tableName`";
        if($conn->query($sql)) {
            $message = "Table '$tableName' dropped successfully";
        } else {
            $error = "Error dropping table: " . $conn->error;
        }
    }

    // After deletion, redirect to the same page to reload the event list
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$excludedTables = ['students', 'courses', 'sections','registration','attendance_settings'];
$excludedTablesStr = "'" . implode("','", $excludedTables) . "'";


$sql = "SHOW TABLES FROM school WHERE Tables_in_school NOT IN ($excludedTablesStr)";
$result = $conn->query($sql);
$tables = array();

while($row = $result->fetch_array()) {
    $tables[] = $row[0];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .blur {
    filter: blur(5px); /* Adjust the blur intensity as needed */
    transition: filter 0.3s ease;
}
</style>
</head>
<body>
    <main class="w-full h-screen bg-gray-800 flex" id="cover-container">
        <div class="fixed bottom-5 right-5">
                <button onclick="openAddModal()" class="bg-indigo-600 text-white p-4 rounded-full hover:bg-indigo-700 transition-all duration-300 ease-in-out transform hover:translate-x-2">
                    <svg id="addIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span id="addText" class="hidden">Add Event</span>
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
                <li><a href="admin-registry.php" class="text-white text-xl ml-4 navbot"><i class="fas fa-solid fa-calendar"></i><span class="ml-10 text-sm">Events</span></a></li>
                <li><a href="admin-attendance.php" class="text-white text-xl ml-4 navbot"><i class="fas fa-clipboard-check"></i><span class="ml-10 text-sm">Attendance</span></a></li>
            </ul> 
        </section>
        <section class="w-full h-screen px-10 py-5 bg-primary-color flex flex-col">
            <div class="mt-3">
                <form action="" class="absolute">
                    <input type="submit" value=""><i class="fas fa-search absolute right-2 z-10 top-1/3"></i></input>
                    <input type="text" placeholder="Search..." class=" rounded-lg px-5 w-96 h-12 relative left-0">
                </form>
            </div>
            <div class="relative w-full h-auto bg-second-color rounded-lg top-20 p-3">
                <h1 class="text-3xl text-white font-bold ml-5 mt-4">Events</h1>
                <table class="w-full divide-y divide-gray-200 rounded-tl-full rounded-tr-full mt-6">
                    <thead class="">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">Event name</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">Time-in</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">Time-out</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">Date</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">Action</th>
                        </tr>
                    </thead> 
                    <tbody class="divide-y divide-gray-200">
                        <?php if ($results->num_rows > 0): ?>
                            <?php while($row = $results->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 text-center text-white"><?php echo htmlspecialchars($row['table_name']); ?></td>
                                    <td class="px-6 py-4 text-center text-white"><?php echo htmlspecialchars($row['time_in']); ?></td>
                                    <td class="px-6 py-4 text-center text-white"><?php echo htmlspecialchars($row['time_out']); ?></td>
                                    <td class="px-6 py-4 text-center text-white"><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap flex space-x-4 justify-center">
                                        <?php if (in_array($row['table_name'], $tables)): ?>
                                            <a href="php/view-attendance.php?table=<?php echo $row['table_name']; ?>"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                View Table
                                            </a>
                                            <form method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to drop this table?');">
                                                <input type="hidden" name="table_name" value="<?php echo $row['table_name']; ?>">
                                                <button type="submit" name="drop_table" class="text-red-600 hover:text-red-900">
                                                    Drop Table
                                                </button>
                                            </form>
                                            <form action="php/print-attendance.php">
                                                <input type="hidden" name="table_name" value="<?php echo $row['table_name']; ?>">
                                                <button type="submit" name="drop_table" class="text-blue-600 hover:text-red-900">
                                                    View PDF
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-white">No events found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <div id="createnew" class="container absolute top-20 left-20 invisible">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 relative">
            <h2 class="text-2xl font-bold mb-6">Create Attendance Sheet</h2>
            <button class="absolute top-5 right-4 w-10 h-10 bg-accent-color rounded-md text-white align-middle items-center" onclick="closeAddModal()"><i class="fa fa-times" aria-hidden="true" ></i></button>
            <?php if(isset($message)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form action="php/create-attendance.php" method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="table_name">Attendance Sheet Name</label>
                    <input type="text" name="table_name" id="table_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="time_in">Time In</label>
                    <input type="time" name="time_in" id="time_in" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="time_out">Time Out</label>
                    <input type="time" name="time_out" id="time_out" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="submit" class="bg-accent-color text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openAddModal(){
            document.getElementById('createnew').classList.remove('invisible');
            document.getElementById('cover-container').classList.add('blur'); 
        }
        function closeAddModal(){
            document.getElementById('createnew').classList.add('invisible');  // Hide the modal
            document.getElementById('cover-container').classList.remove('blur');  
        }
    </script>
</body>
</html>
