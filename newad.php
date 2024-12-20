<?php
// Database connection details
$host = 'localhost';
$dbname = 'medspotter';
$username = 'root';
$password = '';

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['number'] ?? '';
    $storeName = $_POST['storename'] ?? '';
    $address = $_POST['address'] ?? '';
    $storeid = $_POST['storeid'] ?? '';

    // Validate inputs
    if (empty($user) || empty($pass) || empty($email) || empty($phone) || empty($storeName) || empty($address) || empty($storeid)) {
        exit("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit("Invalid email format.");
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        exit("Phone number must be 10 digits.");
    }

    // Hash the password
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // Handle image upload
    if (isset($_FILES['store_image']) && $_FILES['store_image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['store_image']['tmp_name']);
        $imageType = $_FILES['store_image']['type'];

        if (!str_starts_with($imageType, 'image/')) {
            exit("Only image files are allowed.");
        }
    } else {
        exit("Error uploading the image.");
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO admin1 (username, password, email, number, storename, storeid, address, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssssisb", $user, $hashedPassword, $email, $phone, $storeName, $storeid, $address, $imageData);

    if ($stmt->execute()) {
        header("Location: adminprup.html");
        exit();
    } else {
        if ($conn->errno === 1062) {
            echo "This username or email is already registered.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}
$conn->close();
?>
