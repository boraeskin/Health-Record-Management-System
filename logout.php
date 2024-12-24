<?php
// Start the session
session_start();

// Destroy the session and logout the user
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the login page  
header("Location: login.php?logout=success");   
exit();   
?>
