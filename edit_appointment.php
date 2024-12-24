<?php
session_start();
require 'db_connection.php'; // Include the database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$appointment_id = $_GET['id'];

// Check if the logged-in user is an admin or regular user
if ($_SESSION['user_type'] == 'admin') {
    // Admin can edit any appointment
    $query = "SELECT * FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
} else {
    // Regular user can only edit their own appointments
    $query = "SELECT * FROM appointments WHERE appointment_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();

    // If no appointment is found, deny access
    if (!$appointment) {
        die("Unauthorized access.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_date = $_POST['appointment_date'];
    $description = $_POST['description'];

    // Validate for conflicting appointments
    $stmt = $conn->prepare("
        SELECT * 
        FROM appointments 
        WHERE doctor_id = ? 
        AND DATE(appointment_date) = DATE(?) 
        AND HOUR(appointment_date) = HOUR(?) 
        AND appointment_id != ?
    ");
    $stmt->bind_param("issi", $appointment['doctor_id'], $appointment_date, $appointment_date, $appointment_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) { // If a conflicting appointment exists, alert the user
        echo "<script>alert('You cannot change the time to an already booked slot!'); window.history.back();</script>";
        $stmt->close();
        exit;
    }

    // Update the appointment in the database
    $stmt->close();
    $query = "UPDATE appointments SET appointment_date = ?, description = ? WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $appointment_date, $description, $appointment_id);
    $stmt->execute();

    // Redirect based on the user type
    if ($_SESSION['user_type'] == 'admin') {
        echo "<script>alert('Appointment updated successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Appointment updated successfully!'); window.location.href = 'dashboard.php';</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="editAppointment-container">
        <h2>Edit Appointment</h2>
        <form action="" method="POST">
            <input type="datetime-local" name="appointment_date" value="<?= htmlspecialchars($appointment['appointment_date']) ?>" required>
            <textarea name="description" required><?= htmlspecialchars($appointment['description']) ?></textarea>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>