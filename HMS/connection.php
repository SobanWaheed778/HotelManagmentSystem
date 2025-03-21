<?php
// Database credentials
$servername = "localhost"; // Replace with your server name if different
$username = "root";       // Replace with your database username
$password = "";           // Replace with your database password
$dbname = "hms";          // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment the line below for debugging (optional)
// echo "Connected successfully";
?>
