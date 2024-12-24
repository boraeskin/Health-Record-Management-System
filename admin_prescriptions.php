<?php
session_start();
include('db_connection.php');

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Retrieve all prescriptions along with user, doctor, and appointment details
$sql = "SELECT p.appointment_id, p.prescription_text, p.created_at, u.name AS user_name, d.name AS doctor_name, a.appointment_date
        FROM prescriptions p
        JOIN doctors d ON p.doctor_id = d.id
        JOIN users u ON p.user_id = u.id
        JOIN appointments a ON p.appointment_id = a.appointment_id";  
$result = $conn->query($sql);   // Execute the query and store the result
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Prescriptions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin_prescriptions">
    <div class="admin_prescriptions">
    <h1>All Prescriptions</h1>
    <table border="1">
        <tr>
            <th>Appointment ID</th> <!-- Updated column name to appointment_id -->
            <th>User</th>
            <th>Doctor</th>
            <th>Prescription</th>
            <th>Appointment Time</th>
            <th>Date Issued</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['appointment_id']) ?></td> <!-- Updated to appointment_id -->
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
            <td><?= htmlspecialchars($row['prescription_text']) ?></td>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
    <form class="logout" action="logout.php" method="POST" style="display:inline;">
    <button type="submit" style="background-color: red; color: white; border: none; margin-top:20px; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
        Logout
    </button>
</form>

</body>
</html>

<?php 
$conn->close();
?>
