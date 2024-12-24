<?php
session_start();
// Check if the user is logged in by verifying the session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "project");

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];                  // Retrieve form data   
    $doctor_id = $_POST['doctor_id'];              
    $appointment_date = $_POST['appointment_date'];

    // Insert appointment into the database
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $doctor_id, $appointment_date);

    if ($stmt->execute()) {   // Check if the insertion is successful
        $_SESSION['appointment_success'] = "Appointment booked successfully!";

        // Redirect to dashboard after success
        header("Location: dashboard.php");
        exit; 
    } else {
        echo "<h1>Error</h1>";
        echo "<p>Failed to book the appointment. Please try again.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
