<?php
session_start(); // Start the session to get user information
include('header.php'); // Include the header (navbar, etc.)
require 'dbconfig.php'; // Include the database connection

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure the user is logged in by checking the session
if (!isset($_SESSION['id'])) {
    echo "You need to log in to view your wishlist.";
    exit;
}

$userId = $_SESSION['id']; // Get the user ID from the session

// Prepare the SQL query to fetch wishlist items along with product details
$sql = "
    SELECT 
        p.name, 
        p.price, 
        w.added_on
    FROM 
        wishlist w
    JOIN 
        product p ON w.product_id = p.id
    WHERE 
        w.user_id = '$userId'
    ORDER BY 
        w.added_on DESC
";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$wishlistItems = [];

if (mysqli_num_rows($result) > 0) {
    // Fetch all wishlist items and store them in an array
    while ($row = mysqli_fetch_assoc($result)) {
        $wishlistItems[] = $row;
    }
} else {
    echo "Your wishlist is empty.";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <header>
        <?php include('header.php'); ?>
        <!-- Include the navbar here -->
    </header>

    <div class="container">
        <h1>Your Wishlist</h1>
        <?php if (!empty($wishlistItems)): ?>
            <div class="row">
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="width: 18rem;">
                            <img src="./images/books-category.jpg" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text">Price: ₹<?= number_format($item['price'], 2); ?></p>
                                <p class="card-text">Added on: <?= date("F j, Y, g:i a", strtotime($item['added_on'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No products in your wishlist.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
