<?php
// Include the database connection to interact with database
include('db_connection.php');

// Handle form submission when the user submits the signup form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];      
    $email = $_POST['email'];     // Email address provided by the user
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $user_type = $_POST['user_type']; // doctor or user

    // Check if the email already exists in users or doctors table
    $email_check_sql = "SELECT email FROM users WHERE email = ? UNION SELECT email FROM doctors WHERE email = ?";
    $stmt = $conn->prepare($email_check_sql);
    $stmt->bind_param('ss', $email, $email);   // Bind the same email value to both placeholders in the query to check for duplicates in both the 'users' and 'doctors' tables
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        // If the query returned rows, it means email is already registered so its alerts and ask the user to use a different email
        echo "<script>alert('This email is already registered. Please use a different email.'); window.history.back();</script>";
    } else {
        // Check if the user is a doctor
        if ($user_type == 'doctor') {
            // Additional doctor data (specialization)
            $specialization = $_POST['specialization'];

            // Insert query for doctors
            $sql = "INSERT INTO doctors (name, email, password, specialization) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $name, $email, $password, $specialization);
            
            
            if ($stmt->execute()) {
                // Success message for doctor account
                echo "<script>alert('Doctor account created successfully!'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
            }
        } else {
             // If it's a user account, insert the details into the `users` table
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $name, $email, $password);
            
            // Execute the query to insert the user's record
            if ($stmt->execute()) {
                // Success message for user account
                echo "<script>alert('User account created successfully!'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
            }
        }
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">  
</head>
<body class = "signup-page">
<p class = healthcareSystem>Healthcare System</p>
  <div class="signUp-container">
    <div class="signUp-title">Sign Up Form</div>
    
     <!-- Signup form begins -->
    <form action="signup.php" method="POST">
      <div class="input-group">
        <label for="name">Full Name : </label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
      </div>

      <div class="input-group">
        <label for="email">Email Address : </label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="input-group">
        <label for="password">Password : </label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>

      <div class="input-group">
        <label for="user_type">Account Type : </label>
        <select id="user_type" name="user_type" required>
          <option value="user">User</option>
          <option value="doctor">Doctor</option>
        </select>
      </div>
      <!-- Additional field for doctors -->
      <div class="input-group" id="specialization_group" style="display: none;">
        <label for="specialization"> Specialization : </label>
        <input type="text" id="specialization" name="specialization">
      </div>
        <!-- Submit button to create the account -->
      <button type="submit" class="button">Sign Up</button>
    </form>
    <!-- End of signup form -->
    <div class="links">
      <p>Already have an account? <a href="login.php">Login here</a> </p>
    </div>
  </div>

  <script>
    // Event listener to display specialization field only for doctors
    document.getElementById('user_type').addEventListener('change', function () {
      const specializationGroup = document.getElementById('specialization_group');
      if (this.value === 'doctor') {
        specializationGroup.style.display = 'block';
      } else {
        specializationGroup.style.display = 'none';
      }
    });
  </script>
</body>
</html>
