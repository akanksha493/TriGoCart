<?php
session_start();
header('Content-Type: application/json');

// Ensure the cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;
$quantity = intval($data['quantity'] ?? 0);

// Update the cart
if ($productId !== null) {
    if ($quantity > 0) {
        // Update the quantity
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    } else {
        // Remove the product if quantity is 0
        unset($_SESSION['cart'][$productId]);
    }
}

// Calculate the grand total
$grandTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $grandTotal += $item['price'] * $item['quantity'];
}

// Return the updated grand total as JSON
echo json_encode(['grandTotal' => $grandTotal]);
exit;