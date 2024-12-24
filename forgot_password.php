<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Hash the new password for security
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Check if the user exists in the `users` table
    $sql_users = "SELECT id FROM users WHERE name = ? AND email = ?";
    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param('ss', $username, $email);
    $stmt_users->execute();
    $stmt_users->store_result();

    if ($stmt_users->num_rows > 0) {
        // User found in `users` table, update the password
        $update_users = "UPDATE users SET password = ? WHERE name = ? AND email = ?";
        $stmt_update_users = $conn->prepare($update_users);
        $stmt_update_users->bind_param('sss', $hashed_password, $username, $email);

        if ($stmt_update_users->execute()) {
            echo "<script>alert('Password reset successfully for User!'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error resetting password for User.'); window.history.back();</script>";
        }

        $stmt_update_users->close();
    } else {
        // Check if the user exists in the `doctors` table
        $sql_doctors = "SELECT id FROM doctors WHERE name = ? AND email = ?";
        $stmt_doctors = $conn->prepare($sql_doctors);
        $stmt_doctors->bind_param('ss', $username, $email);
        $stmt_doctors->execute();
        $stmt_doctors->store_result();

        if ($stmt_doctors->num_rows > 0) {
            // User found in `doctors` table, update the password
            $update_doctors = "UPDATE doctors SET password = ? WHERE name = ? AND email = ?";
            $stmt_update_doctors = $conn->prepare($update_doctors);
            $stmt_update_doctors->bind_param('sss', $hashed_password, $username, $email);

            if ($stmt_update_doctors->execute()) {
                echo "<script>alert('Password reset successfully for Doctor!'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error resetting password for Doctor.'); window.history.back();</script>";
            }

            $stmt_update_doctors->close();
        } else {
            echo "<script>alert('No account found with that username and email.'); window.history.back();</script>";
        }

        $stmt_doctors->close();
    }

    $stmt_users->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="forgot-password-page">
    <div class="forgot-password-container">
        <h1>Forgot Password</h1>
        <form action="forgot_password.php" method="POST" style="position: relative; left: -20px;">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required><br><br>

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>

          <label for="new_password">New Password:</label>
          <input type="password" id="new_password" name="new_password" placeholder="Enter your new password" required><br><br>

         <button type="submit">Reset Password</button>
        </form>

        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>

