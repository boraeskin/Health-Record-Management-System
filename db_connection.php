<?php
$host = "localhost";             
$user = "root";
$password = "";   // leave empty for default default
$dbname = "project";

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection to ensure it's successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
