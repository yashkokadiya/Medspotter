<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "medspotter"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productname = trim($_POST['productname']);
    $productdescription = trim($_POST['productdescription']);
    $productprice = floatval($_POST['productprice']);
    $storeid = intval($_POST['storeid']); 

    if (empty($productname) || empty($productdescription) || empty($productprice) || $productprice <= 0 || empty($storeid)) {
        die("All fields are required, and price must be greater than 0.");
    }

    $checkStoreQuery = $conn->prepare("SELECT COUNT(*) FROM admin1 WHERE storeid = ?");
    if ($checkStoreQuery === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $checkStoreQuery->bind_param("i", $storeid);
    $checkStoreQuery->execute();
    $checkStoreQuery->bind_result($storeExists);
    $checkStoreQuery->fetch();
    $checkStoreQuery->close();

    if ($storeExists === 0) {
        die("Invalid store ID. You are not authorized to add products to this store.");
    }

    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['productImage']['tmp_name'];
        $imageData = file_get_contents($imageTmpName);
    } else {
        die("Image upload failed. Please upload a valid image.");
    }

    $stmt = $conn->prepare("INSERT INTO product (storeid, productname, productdescription, productprice, image) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("issdb", $storeid, $productname, $productdescription, $productprice, $imageData);

    $stmt->send_long_data(4, $imageData);

    // Updated redirection logic
    if ($stmt->execute()) {
        header("Location: view_store_products.php?storeid=" . $storeid);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
