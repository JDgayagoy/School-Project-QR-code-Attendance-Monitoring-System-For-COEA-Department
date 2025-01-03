<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="images\logo.png" alt="Logo" class="h-14" >
                        <span class="text-xl font-bold">Student Portal</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-4">Welcome, <?php echo $_SESSION['student_id']; ?></span>
                    <a href="php/logoutAction.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <main class="container mx-auto px-4 py-8">