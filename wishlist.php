<?php
// Start session to track logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$servername = "localhost"; // Update with your server details
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "ecomm";         // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['id'];

// Add to wishlist logic
if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];

    // Check if the product is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If not in wishlist, add it
        $insertQuery = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
}

// Remove from wishlist logic
if (isset($_POST['remove_from_wishlist'])) {
    $product_id = $_POST['product_id'];

    // Remove product from the wishlist
    $deleteQuery = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

// Fetch products in the user's wishlist
$wishlistQuery = "
    SELECT product.id, product.name, product.description, product.price, product.category
    FROM wishlist
    INNER JOIN product ON wishlist.product_id = product.id
    WHERE wishlist.user_id = ?
";
$stmt = $conn->prepare($wishlistQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlistResult = $stmt->get_result();

// Fetch all products (for adding to wishlist functionality)
$productQuery = "SELECT * FROM product";
$productResult = $conn->query($productQuery);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
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
                    <tbody id="wishlist-items">
                        <?php if ($wishlistResult->num_rows > 0): ?>
                            <?php while ($row = $wishlistResult->fetch_assoc()): ?>
                                <tr data-product-id="<?= $row['id']; ?>">
                                    <td><?= htmlspecialchars($row['name']); ?></td>
                                    <td><?= htmlspecialchars($row['description']); ?></td>
                                    <td>₹<?= number_format($row['price'], 2); ?></td>
                                    <td><?= htmlspecialchars($row['category']); ?></td>
                                    <td>
                                        <!-- Remove button -->
                                        <form action="wishlist.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="remove_from_wishlist" class="btn-remove">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Your wishlist is empty</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add products to wishlist -->
        <div class="add-to-wishlist">
            <?php if ($productResult->num_rows > 0): ?>
                <?php while ($row = $productResult->fetch_assoc()): ?>
                    <?php
                    // Check if the product is already in the wishlist
                    $isInWishlistQuery = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
                    $stmt = $conn->prepare($isInWishlistQuery);
                    $stmt->bind_param("ii", $user_id, $row['id']);
                    $stmt->execute();
                    $isInWishlist = $stmt->get_result()->num_rows > 0;
                    ?>
                    <?php if (!$isInWishlist): ?>
                        <form action="wishlist.php" method="POST" class="wishlist-form">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="add_to_wishlist" class="btn-add">Add to Wishlist</button>
                        </form>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const removeButtons = document.querySelectorAll(".btn-remove");

            removeButtons.forEach(button => {
                button.addEventListener("click", async (event) => {
                    event.preventDefault();
                    const form = button.closest('form');
                    const productId = form.querySelector('input[name="product_id"]').value;

                    const response = await fetch("wishlist.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            remove_from_wishlist: true,
                        }),
                    });

                    if (response.ok) {
                        form.closest("tr").remove(); // Remove the item from the table
                    } else {
                        console.error("Failed to remove product from wishlist.");
                    }
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>

<?php
// Close the connection
$conn->close();
?>
