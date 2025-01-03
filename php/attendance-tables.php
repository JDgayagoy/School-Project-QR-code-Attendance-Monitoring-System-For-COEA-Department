<?php
include 'cont.php';

// Handle table deletion
if(isset($_POST['drop_table'])) {
    $tableName = $_POST['table_name'];
    $sql = "DROP TABLE IF EXISTS `$tableName`";
    if($conn->query($sql)) {
        $message = "Table '$tableName' dropped successfully";
    } else {
        $error = "Error dropping table: " . $conn->error;
    }
}

// Define tables to exclude
$excludedTables = ['students', 'courses', 'sections'];
$excludedTablesStr = "'" . implode("','", $excludedTables) . "'";

// Modified query to exclude specific tables
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
    <title>Manage Attendance Tables</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Manage Attendance Tables</h1>

        <?php if(isset($message)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach($tables as $table): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $table; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-4">
                            <a href="view-attendance.php?table=<?php echo $table; ?>"
                                class="text-indigo-600 hover:text-indigo-900">
                                View Table
                            </a>
                            <form method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to drop this table?');">
                                <input type="hidden" name="table_name" value="<?php echo $table; ?>">
                                <button type="submit" name="drop_table" class="text-red-600 hover:text-red-900">
                                    Drop Table
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>