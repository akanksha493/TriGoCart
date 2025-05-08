<?php
session_start();

// Include database configuration
require 'dbconfig.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id']; // Get the logged-in user's ID

// Fetch user details
$queryUser = "SELECT * FROM users WHERE id = '$userId'";
$userResult = mysqli_query($conn, $queryUser);

// Check if the query was successful
if (!$userResult) {
    die("Error executing query: " . mysqli_error($conn));
}

// Fetch the user as an associative array
$user = mysqli_fetch_assoc($userResult);

// Ensure $user is an array
if (!$user) {
    echo "User not found or no data returned.";
    exit;
}

// Fetch user orders
$queryOrders = "SELECT * FROM orders WHERE user_id = '$userId' ORDER BY order_date DESC";
$ordersResult = mysqli_query($conn, $queryOrders);

// Check if the query was successful
if (!$ordersResult) {
    die("Error executing query: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- <header>
        <?php 
        // include('header.php'); 
        ?>
    </header> -->
    <header>
        <nav class="navgunj">
            <div class="gunjfluid">
                <div class="logo">
                    <div class="logo1"></div>
                </div>
                <div style="display: flex; justify-content: center; height: 6vh;">
                    <div style="padding: 2.5vh 0vh; margin-left:2rem;">
                        <input id="search" type="text" placeholder="Search for products..." />
                        <button class="go-bttn">Go</button>
                    </div>
                </div>
                <div class="navlist">
                    <div class="nav-item">
                        <a href="index.php" style="text-decoration: none;">
                            <div class="navdiv">Home</div>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="get_electronics.php" style="text-decoration: none;">
                            <div class="navdiv">Electronics</div>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="get_cloths.php" style="text-decoration: none;">
                            <div class="navdiv">Apparel</div>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="get_books.php" style="text-decoration: none;">
                            <div class="navdiv">Books</div>
                        </a>
                    </div>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <div class="nav-item"><a href="wishlist.php" style="text-decoration: none;">
                            <div class="navdiv">Wishlist</div>
                        </a></div>
                    <div class="nav-item"><a href="cart.php" style="text-decoration: none;">
                            <div class="navdiv">Cart</div>
                        </a></div>
                    <?php else: ?>
                    <div class="nav-item"><a href="register.php" style="text-decoration: none;">
                            <div class="navdiv">Register</div>
                        </a></div>
                    <?php endif; ?>
                    <div class="nav-item">
                        <a href="profile.php" style="text-decoration: none;">
                            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                                <div class="navdiv">
                                    <i class="fa-regular fa-user icon" aria-hidden="true"></i>
                                    <?= htmlspecialchars($user['first_name']); ?>
                                </div>
                            <?php else: ?>
                                <div class="navdiv">Log in</div>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="nav-item">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                            <a href="logout.php" style="text-decoration: none;">
                                <div class="navdiv">Log out</div>
                            </a>
                        <?php else: ?>
                            <a href="login.php" style="text-decoration: none;">
                                <div class="navdiv">Log in</div>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    

    <div class="container mt-6">
        <h2>Welcome, <?= htmlspecialchars($user['first_name']); ?> <?= htmlspecialchars($user['last_name']); ?></h2>
        <hr>
        <h4>Your Information</h4>
        <ul class="list-group">
            <li class="list-group-item"><strong>Username:</strong> <?= htmlspecialchars($user['username']); ?></li>
            <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($user['phone']); ?></li>
            <li class="list-group-item"><strong>Country:</strong> <?= htmlspecialchars($user['country']); ?></li>
        </ul>

        <hr>
        <h4>Your Orders</h4>
        <?php if (mysqli_num_rows($ordersResult) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($ordersResult)): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']); ?></td>
                            <td><?= date('d-m-Y H:i:s', strtotime($order['order_date'])); ?></td>
                            <td><?= htmlspecialchars($order['status']); ?></td>
                            <td>₹<?= number_format($order['total_amount'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no orders yet.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
