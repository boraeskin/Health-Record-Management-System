<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Retrieve all appointments along with user and doctor details
$query = "
    SELECT 
        a.appointment_id AS appointment_id, 
        a.appointment_date, 
        a.description, 
        u.name AS user_name, 
        d.name AS doctor_name, 
        d.specialization 
    FROM 
        appointments a 
    JOIN 
        users u ON a.user_id = u.id 
    JOIN 
        doctors d ON a.doctor_id = d.id
";
$result = $conn->query($query);   // Execute the query and store the result
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="adminDashboard-container">
        <h1>Admin Dashboard</h1>
        <h2>All Appointments</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Appointment Date</th>
                        <th>Description</th>
                        <th>User</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['appointment_id']) ?></td>
                            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($row['specialization']) ?></td>
                            <td>
                                <a href="edit_appointment.php?id=<?= $row['appointment_id'] ?>">Edit</a> | 
                                <a href="delete_appointment.php?id=<?= $row['appointment_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>

        <h3>Create New Appointment</h3>
        <form action="create_appointment.php" method="POST">
            <label for="user_id">User:</label>
            <select id="user_id" name="user_id" required>
                <option value="">-- Select User --</option>
                <?php
                $user_query = "SELECT id, name FROM users";
                $user_result = $conn->query($user_query);
                while ($user_row = $user_result->fetch_assoc()) {
                    echo "<option value='{$user_row['id']}'>" . htmlspecialchars($user_row['name']) . "</option>";
                }
                ?>
            </select><br><br>

            <label for="doctor_id">Doctor:</label>
            <select id="doctor_id" name="doctor_id" required>
                <option value="">-- Select Doctor --</option>
                <?php
                $doctor_query = "SELECT id, name FROM doctors";
                $doctor_result = $conn->query($doctor_query);
                while ($doctor_row = $doctor_result->fetch_assoc()) {
                    echo "<option value='{$doctor_row['id']}'>" . htmlspecialchars($doctor_row['name']) . "</option>";
                }
                ?>
            </select><br><br>

            <label for="appointment_date">Appointment Date:</label>
            <input type="datetime-local" id="appointment_date" name="appointment_date" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Enter description" required></textarea><br><br>

            <button type="submit">Create Appointment</button>
        </form>

        <a href="admin_prescriptions.php"><button type="button">View All Prescriptions</button></a>
        <form class="logout" action="logout.php" method="POST" style="display:inline;">
            <button type="submit" style="background-color: red;">Logout</button>
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection when the script ends
$conn->close();
?>
