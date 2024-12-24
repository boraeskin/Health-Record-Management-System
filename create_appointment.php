<?php
session_start();
require 'db_connection.php';   // Include the database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['user', 'admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the logged-in user type
    $user_type = $_SESSION['user_type'];
    $user_id = ($user_type === 'user') ? $_SESSION['user_id'] : $_POST['user_id'];  
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $description = $_POST['description'];

    // Validate the form inputs to ensure no fields are empty
    if (empty($user_id) || empty($doctor_id) || empty($appointment_date) || empty($description)) {
        echo "<script>alert('Please fill in all fields!'); window.history.back();</script>";
        exit;
    }

    // Check if an appointment already exists for the same doctor at the same time
    $stmt = $conn->prepare("
        SELECT * 
        FROM appointments 
        WHERE doctor_id = ? 
        AND DATE(appointment_date) = DATE(?) 
        AND HOUR(appointment_date) = HOUR(?)
    ");
    
    $stmt->bind_param("iss", $doctor_id, $appointment_date, $appointment_date);   // Bind the parameters to the SQL query
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {         // If a conflicting appointment exists, alert the user and redirect them back to the form
        echo "<script>alert('Appointment slot is already booked for this hour. Please choose a different time!'); window.history.back();</script>";
        $stmt->close();
        exit;
    }

    // Insert the new appointment into the database
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $doctor_id, $appointment_date, $description);

    if ($stmt->execute()) {               // Check if the query was executed successfully
        if ($user_type === 'user') {
            echo "<script>alert('Appointment created successfully!'); window.location.href = 'dashboard.php';</script>";
        } else if ($user_type === 'admin') {
            echo "<script>alert('Appointment created successfully!'); window.location.href = 'admin_dashboard.php';</script>";
        }
        exit;
    } else {
        echo "<script>alert('Error creating appointment! Please try again.'); window.history.back();</script>";
        exit;
    }

    $stmt->close();
}

$conn->close();       // Close the database connection
?>
