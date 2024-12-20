<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'medspotter'; 
$username = 'root'; 
$password = '';     
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $storeid = $_POST['storeid'] ?? ''; 

 
    if (empty($user) || empty($pass) || empty($storeid)) {
        exit("Username, password, and store ID cannot be empty.");
    }

    $stmt = $conn->prepare("SELECT password FROM admin1 WHERE username = ? AND storeid = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("si", $user, $storeid);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($pass, $hashedPassword)) {
            header("Location: adminprup.html");
            exit;
        } else {
            echo "Wrong password.";
        }
    } else {
        echo "Invalid username or store ID.";
    }

    $stmt->close();
}

$conn->close();
?>
