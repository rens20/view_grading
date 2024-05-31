<?php
require_once(__DIR__ . '/../config/configuration.php');

require_once(__DIR__ . '/../config/validation.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || $_SESSION['user_id'] != $_GET['id']) {
    header("Location: login.php");
    exit();
}
    

// Fetch user data from the session
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_type = $_SESSION['user_type'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <h2 class="text-2xl font-bold mb-6 text-center">User Dashboard</h2>
        <div class="mb-4">
            <p>Welcome, <?php echo htmlspecialchars($user_name); ?>!</p>
            <p>User Type: <?php echo htmlspecialchars($user_type); ?></p>
        </div>
        <div class="mb-4">
            <a href="logout.php" class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
        </div>
    </div>
</div>

</body>
</html>
