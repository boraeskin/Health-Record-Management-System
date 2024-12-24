<?php
session_start();

require 'db_connection.php';   // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");   // If the user is not logged in, redirect them to the login page
    exit; 
}

$user_id = $_SESSION['user_id']; // Retrieve the currently logged-in user

// Retrieve the users appointments with details about the doctor
$query = "SELECT a.appointment_id, a.appointment_date, a.description, d.name AS doctor_name, d.specialization 
          FROM appointments a 
          JOIN doctors d ON a.doctor_id = d.id 
          WHERE a.user_id = ?";
$stmt = $conn->prepare($query);    // Prepare the query to prevent SQL injection
$stmt->bind_param("i", $user_id);   // Bind the user's ID to the query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <h2>My Appointments</h2>
         <!-- Check if the user has any appointments -->
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Appointment Time</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through each appointment and display it in a table row -->
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['appointment_id']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($row['specialization']) ?></td>
                            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td>
                                <a href="edit_appointment.php?id=<?= $row['appointment_id'] ?>">Edit</a> | 
                                <a href="delete_appointment.php?id=<?= $row['appointment_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You don't have any appointments scheduled.</p>
        <?php endif; ?>

        <div class="create-appointment-section">
           <h3>Create New Appointment</h3>
           <form action="create_appointment.php" method="POST">
           <label for="doctor_id">Choose a Doctor:</label>
           <select id="doctor_id" name="doctor_id" required>
           <option value="">-- Select a Doctor --</option>
                <?php
                // Retrieve available doctors to populate the selection menu
                $doctor_query = "SELECT id, name, specialization FROM doctors";
                $doctor_result = $conn->query($doctor_query);

                if ($doctor_result && $doctor_result->num_rows > 0) {
                    while ($doctor_row = $doctor_result->fetch_assoc()) {
                        echo "<option value='{$doctor_row['id']}'>" . htmlspecialchars($doctor_row['name']) . " ({$doctor_row['specialization']})</option>";
                    }
                } else {
                    echo "<option value=''>No doctors available</option>";
                }
                ?>
            </select><br><br>

               <label for="appointment_date">Appointment Time:</label>
               <input type="datetime-local" id="appointment_date" name="appointment_date" required><br><br>

               <label for="description">Description:</label>
               <textarea id="description" name="description" placeholder="Enter description" required></textarea><br><br>

               <button type="submit">Create Appointment</button>
               </form>
               </div>

        <a href="user_prescriptions.php">
            <button type="button" class="seeUser_prescriptions">View My Prescriptions</button>
        </a>

        <form class= "logout" action="logout.php" method="POST" style="display:inline;">
            <button type="submit" style="background-color: red; color: white; border: none; margin-top:20px; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                Logout
            </button>
        </form>
    </div>
</body>
</html>

<?php
// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
