<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "medspotter"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products based on search query
function fetchProducts($conn, $searchQuery = '') {
    if (!empty($searchQuery)) {
        // Search query is provided, prioritize matches
        $sql = "SELECT productid, productname, productdescription, productprice, image 
                FROM product 
                WHERE productname LIKE ? 
                ORDER BY productname LIKE ? DESC, productname ASC";
        $stmt = $conn->prepare($sql);
        $searchTerm = '%' . $searchQuery . '%';
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // No search query, fetch all products
        $sql = "SELECT productid, productname, productdescription, productprice, image FROM product";
        $result = $conn->query($sql);
    }

    // Return fetched results
    return $result;
}
?>
