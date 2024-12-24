<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'doctor') {
    header("Location: login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];

// Handle appointment addition for any user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_appointment'])) {
    $user_id = $_POST['user_id'];
    $appointment_date = $_POST['appointment_date'];
    $description = $_POST['description'];

    // Check for overlapping appointments
    $check_overlap_query = "
        SELECT COUNT(*) as count 
        FROM appointments 
        WHERE doctor_id = ? 
        AND ABS(TIMESTAMPDIFF(MINUTE, appointment_date, ?)) < 60
    ";
    $check_stmt = $conn->prepare($check_overlap_query);
    $check_stmt->bind_param("is", $doctor_id, $appointment_date);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $overlap = $check_result->fetch_assoc()['count'];

    if ($overlap > 0) {
        echo "<script>alert('The selected time slot is already taken. Please choose a different time.');</script>";
    } else {
        // Insert new appointment
        $add_appointment_query = "
            INSERT INTO appointments (doctor_id, user_id, appointment_date, description) 
            VALUES (?, ?, ?, ?)
        ";
        $add_stmt = $conn->prepare($add_appointment_query);
        $add_stmt->bind_param("iiss", $doctor_id, $user_id, $appointment_date, $description);
        if ($add_stmt->execute()) {
            echo "<script>alert('Appointment added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding appointment.');</script>";
        }
    }
}
// Retrieve doctor’s appointments
$appointments_query = "
    SELECT a.appointment_id, a.user_id, u.name AS user_name, a.appointment_date, a.description
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    WHERE a.doctor_id = ?
";
$stmt = $conn->prepare($appointments_query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$appointments_result = $stmt->get_result();

// Handle appointment updates and deletes
if (isset($_POST['update_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_date = $_POST['new_date'];
    $new_description = $_POST['new_description'];

    // Check for overlapping appointments
    $check_overlap_query = "
        SELECT COUNT(*) as count 
        FROM appointments 
        WHERE doctor_id = ? 
        AND ABS(TIMESTAMPDIFF(MINUTE, appointment_date, ?)) < 60
        AND appointment_id != ?
    ";
    $check_stmt = $conn->prepare($check_overlap_query);
    $check_stmt->bind_param("isi", $doctor_id, $new_date, $appointment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $overlap = $check_result->fetch_assoc()['count'];

    if ($overlap > 0) {
        echo "<script>alert('The selected time slot is already taken. Please choose a different time.');</script>";
    } else {
        // Update appointment
        $update_query = "
            UPDATE appointments 
            SET appointment_date = ?, description = ? 
            WHERE appointment_id = ?
        ";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $new_date, $new_description, $appointment_id);
        if ($update_stmt->execute()) {
            echo "<script>alert('Appointment updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating appointment.');</script>";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    // Delete appointment
    $delete_query = "DELETE FROM appointments WHERE appointment_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $appointment_id);
    if ($delete_stmt->execute()) {
        echo "<script>alert('Appointment deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting appointment: " . $delete_stmt->error . "');</script>";
    }
}




// Retrieve prescriptions for the doctor
$prescriptions_query = "
    SELECT p.id, p.appointment_id, u.name AS user_name, p.prescription_text, p.created_at
    FROM prescriptions p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    JOIN users u ON a.user_id = u.id
    WHERE p.doctor_id = ?
";
$prescription_stmt = $conn->prepare($prescriptions_query);
$prescription_stmt->bind_param("i", $doctor_id);
$prescription_stmt->execute();
$prescriptions_result = $prescription_stmt->get_result();

// Handle prescription add, update, and delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_prescription'])) {
        $appointment_id = $_POST['appointment_id'];
        $prescription_text = $_POST['prescription_text'];

        // Ensure prescription text is not empty
        if (empty($prescription_text)) {
            echo "<script>alert('Prescription text cannot be empty!');</script>";
            return;
        }

        // Validate appointment_id and prescription_text before proceeding
        if (isset($appointment_id) && isset($prescription_text)) {
            // Prepare the query
            $add_prescription_query = "
                INSERT INTO prescriptions (appointment_id, doctor_id, user_id, prescription_text)
                SELECT ?, ?, a.user_id, ?
                FROM appointments a
                WHERE a.appointment_id = ?
            ";

            // Prepare the statement
            $add_prescription_stmt = $conn->prepare($add_prescription_query);
            $add_prescription_stmt->bind_param("iiis", $appointment_id, $doctor_id, $prescription_text, $appointment_id);

            // Execute the statement
            if ($add_prescription_stmt->execute()) {
                echo "<script>alert('Prescription added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding prescription: " . $add_prescription_stmt->error . "');</script>";
            }
        }
    }

    // Prescription Update and Delete
    if (isset($_POST['update_prescription'])) {
        $prescription_id = $_POST['prescription_id'];
        $new_prescription_text = $_POST['new_prescription_text'];

        // Update prescription
        $update_prescription_query = "UPDATE prescriptions SET prescription_text = ? WHERE id = ?";
        $update_prescription_stmt = $conn->prepare($update_prescription_query);
        $update_prescription_stmt->bind_param("si", $new_prescription_text, $prescription_id);
        if ($update_prescription_stmt->execute()) {
            echo "<script>alert('Prescription updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating prescription.');</script>";
        }
    } elseif (isset($_POST['delete_prescription'])) {
        $prescription_id = $_POST['prescription_id'];

        // Delete prescription
        $delete_prescription_query = "DELETE FROM prescriptions WHERE id = ?";
        $delete_prescription_stmt = $conn->prepare($delete_prescription_query);
        $delete_prescription_stmt->bind_param("i", $prescription_id);
        if ($delete_prescription_stmt->execute()) {
            echo "<script>alert('Prescription deleted successfully!');</script>";
        } else {
            echo "<script>alert('Error deleting prescription.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="doctorDashboard-container">
        <h1>Doctor Dashboard</h1>

        <!-- Manage Appointments Section -->
        <h2>Manage Appointments</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>User Name</th>
                    <th>Appointment Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['appointment_id']) ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <!-- Edit Appointment -->
                            <button type="button" onclick="editAppointment(<?= $row['appointment_id'] ?>, '<?= $row['appointment_date'] ?>', '<?= addslashes($row['description']) ?>')">Edit</button>
                            <form class="delete" action="doctor_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                                <button class="delete" type="submit" name="delete_appointment">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Add New Appointment</h3>
        <form action="doctor_dashboard.php" method="POST">
            <label for="user_id">Select User:</label>
            <select name="user_id" required>
                <?php
                $users_query = "SELECT id, name FROM users";
                $user_stmt = $conn->prepare($users_query);
                $user_stmt->execute();
                $users_result = $user_stmt->get_result();
                while ($user_row = $users_result->fetch_assoc()):
                ?>
                    <option value="<?= $user_row['id'] ?>"><?= $user_row['name'] ?></option>
                <?php endwhile; ?>
            </select>
            <br><br>
            <label for="appointment_date">Appointment Date:</label>
            <input type="datetime-local" name="appointment_date" required>
            <br><br>
            <label for="description">Description:</label>
            <textarea name="description" rows="4" required></textarea>
            <br><br>
            <button type="submit" name="add_appointment" class="add_appointment">Add Appointment</button>
        </form>

        <!-- Manage Prescriptions Section -->
        <h3>Manage Prescriptions</h3>
        <table class="styled-table">
    <thead>
        <tr>
            <th>Prescription ID</th>
            <th>User Name</th>
            <th>Prescription Text</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
                <?php while ($row = $prescriptions_result->fetch_assoc()): ?>
                    <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['prescription_text']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <!-- Edit Prescription -->
                    <button type="button" onclick="editPrescription(<?= $row['id'] ?>, '<?= addslashes($row['prescription_text']) ?>')">Edit</button>
                    <form class="delete" action="doctor_dashboard.php" method="POST" style="display:inline;">
                        <input type="hidden" name="prescription_id" value="<?= $row['id'] ?>">
                        <button class= "delete" type="submit" name="delete_prescription">Delete</button>
                    </form>
                </td>
            </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="doctor_prescription.php">
            <button type="button" class="add_appointment">Add Prescriptions</button>
        </a>

        <!-- Logout Button -->
        <form class= "logout" action="logout.php" method="POST" style="display:inline;">
            <button type="submit" style="background-color: red; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                Logout
            </button>
        </form>

        <!-- Edit Appointment Modal -->
        <div id="editAppointmentModal" style="display:none;">
            <h3>Edit Appointment</h3>
            <form action="doctor_dashboard.php" method="POST">
                <input type="hidden" name="appointment_id" id="editAppointmentId">
                <label for="new_appointment_date">New Appointment Date:</label>
                <input type="datetime-local" name="new_date" id="newAppointmentDate" required>
                <br><br>
                <label for="new_description">New Description:</label>
                <textarea name="new_description" id="newDescription" rows="4" required></textarea>
                <br><br>
                <button type="submit" name="update_appointment">Update Appointment</button>
            </form>
        </div>

        <!-- Edit Prescription Modal -->
        <div id="editPrescriptionModal" style="display:none;">
            <h3>Edit Prescription</h3>
            <form action="doctor_dashboard.php" method="POST">
                <input type="hidden" name="prescription_id" id="editPrescriptionId">
                <label for="new_prescription_text">New Prescription Text:</label>
                <textarea name="new_prescription_text" id="newPrescriptionText" rows="4" required></textarea>
                <br><br>
                <button type="submit" name="update_prescription">Update Prescription</button>
            </form>
        </div>
    </div>

    <script>
        function editAppointment(appointmentId, appointmentDate, description) {
            document.getElementById('editAppointmentId').value = appointmentId;
            document.getElementById('newAppointmentDate').value = appointmentDate;
            document.getElementById('newDescription').value = description;
            document.getElementById('editAppointmentModal').style.display = 'block';
        }

        function editPrescription(prescriptionId, prescriptionText) {
            document.getElementById('editPrescriptionId').value = prescriptionId;
            document.getElementById('newPrescriptionText').value = prescriptionText;
            document.getElementById('editPrescriptionModal').style.display = 'block';
        }
    </script>
</body>
</html>

