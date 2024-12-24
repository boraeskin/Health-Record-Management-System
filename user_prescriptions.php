<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header('Location: login.php');
    exit;
}

// Retrieve prescriptions for the logged-in user
$user_id = $_SESSION['user_id'];

$sql = "SELECT p.id, p.prescription_text, p.created_at, d.name AS doctor_name, a.appointment_date
        FROM prescriptions p
        JOIN doctors d ON p.doctor_id = d.id
        JOIN appointments a ON p.appointment_id = a.appointment_id  -- Update the column name if needed
        WHERE p.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);    // Bind the user ID to the query
$stmt->execute();
$result = $stmt->get_result();    // Retrieve the result set from the executed query

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Prescriptions</title>
    <link rel="stylesheet" href="style.css">
</head>
</head>
<body class="user-prescriptions">
    <h1>My Prescriptions</h1>
    <table border="1">
        <tr>
            <th>ID</th>               
            <th>Prescription</th>
            <th>Doctor</th>
            <th>Appointment Time</th>
            <th>Date Issued</th>
        </tr>
          <!-- Loop through the prescriptions and display them -->
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>  <!-- Display the prescription ID -->
            <td><?= htmlspecialchars($row['prescription_text']) ?></td>   <!-- Display the prescription text -->
            <td><?= htmlspecialchars($row['doctor_name']) ?></td>   
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <form class= "logout" action="logout.php" method="POST" style="display:inline;">
            <button type="submit" style="background-color: red; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                Logout
            </button>
        </form>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
