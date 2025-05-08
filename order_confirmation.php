<?php
session_start();
include('dbconfig.php'); // Include your database configuration

// Check if order_id is set in the URL
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Fetch order details
    $orderQuery = "SELECT * FROM orders WHERE id = '$orderId'";
    $orderResult = mysqli_query($conn, $orderQuery);

    if ($orderResult && mysqli_num_rows($orderResult) > 0) {
        $order = mysqli_fetch_assoc($orderResult);

        // Fetch order items
        $itemsQuery = "SELECT oi.*, p.name AS product_name FROM order_items oi 
                       JOIN product p ON oi.product_id = p.id 
                       WHERE oi.order_id = '$orderId'";
        $itemsResult = mysqli_query($conn, $itemsQuery);

        if (!$itemsResult) {
            echo "Error fetching order items: " . mysqli_error($conn);
            exit();
        }

    } else {
        echo "Order not found.";
        exit();
    }
} else {
    echo "Invalid order ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Order Confirmation</h2>

        <div class="order-details">
            <h4>Order #<?= htmlspecialchars($order['id']); ?></h4>
            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
            <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_amount'], 2); ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']); ?></p>

            <h4>Order Items</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($itemsResult)): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']); ?></td>
                        <td><?= htmlspecialchars($item['quantity']); ?></td>
                        <td>₹<?= number_format($item['price'], 2); ?></td>
                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
