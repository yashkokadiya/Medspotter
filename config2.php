<?php
// Database connection details
$host = 'localhost';     // Database server (localhost for local development)
$dbname = 'medspotter';  // The name of your database
$username = 'root';      // The database username
$password = '';          // The database password (empty for local setups)

// Create connection to the MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Display error if connection fails
}
?>
