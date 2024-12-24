<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'doctor') {
    header("Location: login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];

// Handle prescription submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $prescription = $_POST['prescription'];

    // Debugging: Check if form data is received correctly
    if (empty($appointment_id) || empty($prescription)) {
        echo "<script>alert('Appointment ID or Prescription is empty.');</script>";
    } else {
        // Get the user_id from the selected appointment
        $appointment_query = "SELECT user_id FROM appointments WHERE appointment_id = ?";
        $stmt = $conn->prepare($appointment_query);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $appointment_result = $stmt->get_result();
        
        if ($appointment_result->num_rows > 0) {
            $appointment_row = $appointment_result->fetch_assoc();
            $user_id = $appointment_row['user_id'];

            // Insert the prescription into the 'prescriptions' table
            $stmt = $conn->prepare("INSERT INTO prescriptions (appointment_id, doctor_id, user_id, prescription_text) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $appointment_id, $doctor_id, $user_id, $prescription);

            if ($stmt->execute()) {
                // Redirect to doctor_dashboard.php after successful save
                header("Location: doctor_dashboard.php");
                exit;  // Make sure the script stops executing here
            } else {
                // Debugging: Show error if the query fails
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
        } else {
            // Debugging: Check if the appointment exists
            echo "<script>alert('Appointment not found.');</script>";
        }
    }
}

// Fetch doctor’s appointments
$appointments_query = "SELECT a.appointment_id, a.user_id, u.name AS user_name, a.appointment_date
                       FROM appointments a
                       JOIN users u ON a.user_id = u.id
                       WHERE a.doctor_id = ?";
$stmt = $conn->prepare($appointments_query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="assignPrescription-container">
    <h1 class="doctorForm">Doctor Dashboard</h1>

    <h2 class="doctorForm">Assign Prescription</h2>
    <form class="doctorForm" action="doctor_prescription.php" method="POST">
        <label for="appointment_id">Select Appointment:</label>
        <select name="appointment_id" required>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['appointment_id']; ?>">
                    <?php echo "User: " . $row['user_name'] . " | Time: " . $row['appointment_date']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="prescription">Prescription:</label>
        <textarea name="prescription" rows="5" cols="30" required></textarea>
        <br><br>

        <button type="submit">Save Prescription</button>
    </form>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
