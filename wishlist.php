<?php
// Start the session to manage the user's wishlist
session_start();

// Database connection
$servername = "localhost"; // Update with your server details
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "ecomm"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add to wishlist logic
if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }

    if (!in_array($product_id, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $product_id;
    }
}

// Remove from wishlist logic
if (isset($_POST['remove_from_wishlist'])) {
    $product_id = $_POST['product_id'];
    if (($key = array_search($product_id, $_SESSION['wishlist'])) !== false) {
        unset($_SESSION['wishlist'][$key]);
    }
}

// Fetch products
$sql = "SELECT * FROM product";
$result = $conn->query($sql);

// Fetch wishlist products
$wishlist = [];
if (isset($_SESSION['wishlist'])) {
    $wishlist = $_SESSION['wishlist'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="style.css"> 
</head>

<body>
    <header>
          <?php include('header.php'); ?>
     </header>
    <div class="cart-container">
        <div class="cart-title">Your Wishlist</div>

        <div class="cart-table-wrapper-out">
            <div class="cart-table-wrapper-in">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <?php if (in_array($row['id'], $wishlist)): ?>
                                    <tr>
                                        <td><?= $row['name'] ?></td>
                                        <td><?= $row['description'] ?></td>
                                        <td>$<?= number_format($row['price'], 2) ?></td>
                                        <td><?= $row['category'] ?></td>
                                        <td>
                                            <!-- Remove button -->
                                            <form action="wishlist.php" method="POST" style="display: inline;">
                                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                                <button type="submit" name="remove_from_wishlist" class="btn-remove">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No products available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add products to wishlist -->
        <div class="btn1">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <?php if (!in_array($row['id'], $wishlist)): ?>
                        <form action="wishlist.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="add_to_wishlist" class="but">Add to Wishlist</button>
                        </form>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

<?php
// Close the connection
$conn->close();
?>
