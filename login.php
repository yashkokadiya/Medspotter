<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$host = 'localhost';
$dbname = 'medspotter'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = '';     // Replace with your database password

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Validate input
    if (empty($user) || empty($pass)) {
        exit("Username and password cannot be empty.");
    }

    // Prepare SQL query to check for username
    $stmt = $conn->prepare("SELECT password FROM customer WHERE username = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($pass, $hashedPassword)) {
            // Redirect to the dashboard or another page
            header("Location: home.html");
            exit;
        } else {
            echo "Wrong password.";
        }
    } else {
        echo "Username does not exist.";
    }

    $stmt->close();
}

$conn->close();
?>
