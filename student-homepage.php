<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
include 'php/header.php';
include 'php/cont.php';

try {

    date_default_timezone_set('Asia/Manila');
    $currentTime = date('H:i:s');
    $currentDateTime = strtotime($currentTime);

    $tablesQuery = "SELECT table_name, time_in, time_out 
                    FROM attendance_settings 
                    ORDER BY table_name DESC";
    $tablesResult = $conn->query($tablesQuery);

    if (!$tablesResult) {
        throw new Exception("Error fetching attendance tables: " . $conn->error);
    }
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Available Attendance Sheets</h2>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attendance Sheet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php 
                    while($table = $tablesResult->fetch_assoc()) {
                     
                        $tableExistsQuery = "SHOW TABLES LIKE '" . $conn->real_escape_string($table['table_name']) . "'";
                        $tableExists = $conn->query($tableExistsQuery)->num_rows > 0;

                        if (!$tableExists) {
                            continue;
                        }

                        $timeIn = strtotime($table['time_in']);
                        $timeOut = strtotime($table['time_out']);
                        $now = strtotime($currentTime);
                        $gracePeriodIn = 15 * 60; 
                        $gracePeriodOut = 15 * 60; 
                        
                      
                        $checkQuery = "SELECT time_in, time_out FROM `" . $conn->real_escape_string($table['table_name']) . "` 
                                     WHERE student_id = ? AND date = CURDATE()";
                        $stmt = $conn->prepare($checkQuery);
                        
                        if ($stmt === false) {
                            continue;
                        }
                        
                        $stmt->bind_param("s", $student_id);
                        $stmt->execute();
                        $attendance = $stmt->get_result()->fetch_assoc();
                        $stmt->close();
                    ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo $table['table_name']; ?></td>
                            <td class="px-6 py-4"><?php echo date('h:i A', $timeIn); ?></td>
                            <td class="px-6 py-4"><?php echo date('h:i A', $timeOut); ?></td>
                            <td class="px-6 py-4">
                                <?php if($attendance): ?>
                                    <?php if($attendance['time_in'] && $attendance['time_out']): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Completed</span>
                                    <?php elseif($attendance['time_in']): ?>
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Time In Recorded</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full">Not Recorded</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(!$attendance && $now >= ($timeIn - $gracePeriodIn) && $now <= ($timeIn + $gracePeriodIn)): ?>
                                    <a href="php/record-attendance.php?table=<?php echo $table['table_name']; ?>&action=time_in" 
                                       class="text-blue-600 hover:text-blue-900">Record Time In</a>
                                <?php elseif($attendance && !$attendance['time_out'] && $now >= ($timeOut - $gracePeriodOut) && $now <= ($timeOut + $gracePeriodOut)): ?>
                                    <a href="php/record-attendance.php?table=<?php echo $table['table_name']; ?>&action=time_out" 
                                       class="text-blue-600 hover:text-blue-900">Record Time Out</a>
                                <?php else: ?>
                                    <span class="text-gray-400">Not Available</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>