<?php
session_start();
require 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$appointment_id = $_GET['id'];

// Check if the logged-in user is an admin or regular user
if ($_SESSION['user_type'] == 'admin') {
    // Admin can delete any appointment, no need to check user_id
    $query = "DELETE FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
} else {
    // Regular user can only delete their own appointments
    $query = "DELETE FROM appointments WHERE appointment_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $user_id);
    $stmt->execute();
}

// Redirect based on the user type
if ($_SESSION['user_type'] == 'admin') {
    header("Location: admin_dashboard.php");
} else {
    header("Location: dashboard.php");
}

exit;
?>
