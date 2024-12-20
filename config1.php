<?php
// Database connection configuration
$host = 'localhost'; // Database host (e.g., localhost)
$username = 'root'; // Database username
$password = ''; // Database password
$dbname = 'medspotter'; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Optional: Set character encoding (e.g., for UTF-8)
$conn->set_charset('utf8');

// Function to fetch products (optional helper)
function fetchProducts($conn, $searchQuery = '', $storeid = 0) {
    $query = "SELECT * FROM products";
    if ($storeid > 0) {
        $query .= " WHERE storeid = $storeid";
    }
    if (!empty($searchQuery)) {
        $query .= ($storeid > 0 ? " AND" : " WHERE") . " productname LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
    }
    return $conn->query($query);
}
?>
