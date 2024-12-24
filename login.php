<?php
session_start(); // Start session for login
require 'db_connection.php'; // Include the database connection to interact with the database


if ($_SERVER['REQUEST_METHOD'] === 'POST') {  // Check if the form is submitted via POST method
     // Retrieve data from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['user_type']; // 'user', 'doctor', or 'admin'

        // Validate the user type and prepare the appropriate SQL query
    if ($userType === 'user') {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    } elseif ($userType === 'doctor') {
        $stmt = $conn->prepare("SELECT id, password FROM doctors WHERE email = ?");
    } elseif ($userType === 'admin') {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email = ?");
    } else {
        header("Location: login.php?error=invalid");
        exit;
    }

    // Bind the email parameter to the SQL query and execute it
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashedPassword);  // Bind the result columns to variables

    // Check if informations are valid
    if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
        // Set session variables to track the logged-in user
        $_SESSION['user_id'] = $id;
        $_SESSION['user_type'] = $userType;

        // Redirect to the appropriate dashboard
        if ($userType === 'user') {
            header("Location: dashboard.php"); //for users
        } elseif ($userType === 'doctor') {
            header("Location: doctor_dashboard.php"); // for doctors
        } elseif ($userType === 'admin') {
            header("Location: admin_dashboard.php"); // for admin
        }
        exit;
    } else {
        // Redirect back to login with error
        header("Location: login.php?error=invalid");
        exit;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body class="login-page">
    <link rel="stylesheet" href="style.css">
   
    <p class="healthcareSystem">Healthcare System</p>
    <!-- Login Form -->
    <div class="login-container">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>

            <label for="user_type">I am :</label>
            <select id="user_type" name="user_type" required>
                <option value="user">User</option>
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <button type="submit">Login</button>
        </form>
        <!-- Links to sign up or reset the password -->
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>  
        <p>Forgot your password? <a href="forgot_password.php">Click here</a></p>

    </div>

    <script>
        // Check for the 'logout' or 'error' parameter in the URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('logout') === 'success') {
            alert('You have successfully logged out.');
        } else if (urlParams.get('error') === 'invalid') {
            alert('Invalid email or password. Please try again.');
        }
    </script>
</body>
</html>
