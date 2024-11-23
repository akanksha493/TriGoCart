<?php
session_start(); // Start the session to handle cart data
include('header.php'); // Include the header (navbar, etc.)
require 'dbconfig.php'; // Include the database connection

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle "Add to Cart" action
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productStock = $_POST['product_stock'];

    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Increase quantity if already in the cart
        $_SESSION['cart'][$productId]['quantity'] += 1;
    } else {
        // Add new product to the cart
        $_SESSION['cart'][$productId] = [
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => 1,
            'stock' => $productStock
        ];
    }
}

// Handle "Add to Wishlist" action
if (isset($_POST['add_to_wishlist'])) {
    if (isset($_SESSION['id'])) {  // Ensure the user is logged in
        $userId = $_SESSION['id'];
        $productId = $_POST['product_id'];

        // Check if the product is already in the wishlist
        $checkWishlistQuery = "SELECT * FROM wishlist WHERE user_id = '$userId' AND product_id = '$productId'";
        $checkResult = mysqli_query($conn, $checkWishlistQuery);
        if (mysqli_num_rows($checkResult) == 0) {
            // Insert the product into the wishlist table if it's not already there
            $sql_wishlist = "INSERT INTO wishlist (user_id, product_id) VALUES ('$userId', '$productId')";
            if (mysqli_query($conn, $sql_wishlist)) {
                echo "Product added to wishlist!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "This product is already in your wishlist!";
        }
    } else {
        echo "You need to log in to add to the wishlist.";
    }
}

// Prepare the SQL statement
$sql = "SELECT * FROM product WHERE category='Electronics'";
$result = mysqli_query($conn, $sql); // Use mysqli_query for procedural style

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$products = [];

if (mysqli_num_rows($result) > 0) {
    // Fetch all products
    while ($row = mysqli_fetch_assoc($result)) { // Use mysqli_fetch_assoc for procedural style
        $products[] = $row;
    }
}
// Close the database connection
mysqli_close($conn); // Use mysqli_close for procedural style
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronics</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <header>
        <?php include('header.php'); ?>
        <!-- Include the navbar here -->
    </header>

    <div class="container mt-4">
        <div class="row">
            <?php if (empty($products)): ?>
                <p>No products found in this category.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="width: 18rem;">
                            <!-- Dynamically assign category-specific image -->
                            <img src="./images/electronics-category.jpg" class="card-img-top" alt="Electronics Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($product['description']); ?></p>
                                <p class="card-text"><strong>Price:</strong> ₹<?= htmlspecialchars(number_format($product['price'], 2)); ?></p>
                                <p class="card-text"><strong>Stock:</strong> <?= htmlspecialchars($product['stock_quantity']); ?></p>

                                <!-- Add to Cart Form -->
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']); ?>">
                                    <input type="hidden" name="product_price" value="<?= $product['price']; ?>">
                                    <input type="hidden" name="product_stock" value="<?= $product['stock_quantity']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                </form>

                                <!-- Add to Wishlist Form -->
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                    <button type="submit" name="add_to_wishlist" class="btn btn-secondary">Add to Wishlist</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>
