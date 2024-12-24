<?php
$conn = new mysqli("localhost", "root", "", "project");      // Make a connection to the database

$user_id = 1; // Placeholder for the logged-in user's ID
// Query to retrieve the user's appointments along with doctor details
$result = $conn->query("SELECT a.id, a.appointment_date, d.name AS doctor_name, d.specialization   
                        FROM appointments a
                        JOIN doctors d ON a.doctor_id = d.id
                        WHERE a.user_id = $user_id");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Appointments</title>
</head>
<body>
    <h1>Your Appointments</h1>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>              <!-- Display the appointment ID -->
                        <td><?php echo $row['appointment_date']; ?></td>      <!-- Display the appointment date -->
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['specialization']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</body>
</html>
<?php $conn->close(); ?>
