<?php
// Database connection settings
$servername = "localhost";
$username = "root";  // Default phpMyAdmin username
$password = "";      // Leave empty if no password set
$dbname = "smart_assistant";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
