<?php
$conn = new mysqli("localhost", "root", "", "project");

$doctor_id = 1; // Replace with the logged-in doctor's ID
$result = $conn->query("SELECT a.id, a.appointment_date, u.name AS user_name
                        FROM appointments a
                        JOIN users u ON a.user_id = u.id
                        WHERE a.doctor_id = $doctor_id");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
</head>
<body>
    <h1>Your Appointments</h1>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Patient Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                        <td><?php echo $row['user_name']; ?></td>
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
