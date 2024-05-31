<?php
// user_dashboard_redirect.php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'];

    // Set the user ID in session to redirect later
    $_SESSION['redirect_user_id'] = $user_id;

    echo json_encode(['status' => 'success', 'message' => 'User ID set for redirection']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
