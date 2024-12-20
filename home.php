<?php
require 'config.php'; // Include database connection and query logic

// Get the search query from the GET request
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch products based on the search query
$products = fetchProducts($conn, $searchQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    background-color: #2fad69;
    border-bottom: 2px solid #ddd;
}

.logo img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}


/* Search Container */
.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px;
    padding: 10px;
    background-color: #f9f9f9; /* Light background for the container */
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Form */
.search-container form {
    display: flex;
    align-items: center;
    width: 100%;
    max-width: 500px; /* Restrict the maximum width */
    gap: 8px; /* Spacing between input and button */
}

/* Input Field */
.search-input {
    flex-grow: 1; /* Allow the input to take up available space */
    padding: 10px 15px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
    transition: border-color 0.3s ease;
}

.search-input:focus {
    border-color: #007bff; /* Blue border on focus */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Glow effect */
}

/* Submit Button */
.search-btn {
    background-color: #007bff; /* Blue background */
    border: none;
    border-radius: 5px;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn img {
    width: 20px;
    height: 20px;
    filter: invert(1); /* Makes the icon white */
}

.search-btn:hover {
    background-color: #0056b3; /* Darker blue on hover */
}
.nav ul {
    display: flex;
    list-style-type: none;
}

.nav ul li {
    margin-left: 20px;
}

.nav ul li a {
    text-decoration: none;
    color: #333;
    font-size: 16px;
    transition: color 0.3s;
}

.nav ul li a:hover {
    color: #007BFF;
}
        .product {
            border: 1px solid #ddd;
            padding: 16px;
            margin: 16px;
            text-align: center;
            width: 250px;
            display: inline-block;
        }
        .product img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }
        .product h2 {
            font-size: 1.2rem;
            margin: 10px 0;
        }
        .product p {
            font-size: 1rem;
            color: #555;
        }
        .product .price {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <header class="header">
      <div class="logo">
            <a href="home.php">
            <img src="logo1.jpg" alt="Logo">
            </a>
          </div>
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search..." class="search-input" value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit" class="search-btn">
                    <img src="https://img.icons8.com/material-outlined/24/000000/search--v1.png" alt="Search Icon">
                </button>
            </form>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="health-advice.html">Health Advice</a></li>
                <li><a href="medical-store-near-me.html">Medical Store</a></li>
                <li><a href="contact-us.html">Contact Us</a></li>
                <li><a href="cart.html">Cart</a></li>
            </ul>
        </nav>
    </header>
    <h1>Product List</h1>
    <div>
        <?php if ($products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product">
                    <!-- Display image -->
                    <?php if (!empty($row['image'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" alt="<?= htmlspecialchars($row['productname']) ?>">
                    <?php else: ?>
                        <img src="placeholder.jpg" alt="No Image Available">
                    <?php endif; ?>
                    
                    <!-- Display product details -->
                    <h2><?= htmlspecialchars($row['productname']) ?></h2>
                    <p><?= htmlspecialchars($row['productdescription']) ?></p>
                    <p class="price">$<?= htmlspecialchars(number_format($row['productprice'], 2)) ?></p>
                    <button 
                        class="add-to-cart" 
                        data-name="<?= htmlspecialchars($row['productname']) ?>" 
                        data-price="<?= htmlspecialchars($row['productprice']) ?>" 
                        data-image="<?= !empty($row['image']) ? 'data:image/jpeg;base64,' . base64_encode($row['image']) : 'placeholder.jpg' ?>">
                        Add to Cart
                    </button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>

    <script>
        // JavaScript logic for adding products to cart
        const addToCartButtons = document.querySelectorAll('.add-to-cart');

        addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const name = button.dataset.name;
                const price = parseFloat(button.dataset.price);
                const image = button.dataset.image;

                const cart = JSON.parse(localStorage.getItem('cart')) || [];

                const existingProduct = cart.find(item => item.name === name);

                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    cart.push({ name, price, image, quantity: 1 });
                }

                localStorage.setItem('cart', JSON.stringify(cart));
                alert('Product added to cart!');
            });
        });
    </script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
