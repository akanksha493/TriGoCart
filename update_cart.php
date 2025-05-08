<?php
session_start();
require "dbconfig.php";

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = intval($data['product_id'] ?? 0);
    $quantity = intval($data['quantity'] ?? 0);
    $user_id = $_SESSION['id'];

    if ($product_id <= 0 || $quantity < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
        exit;
    }

    try {
        if ($quantity === 0) {
            // Remove the item if quantity is zero
            $query = "DELETE FROM cart WHERE product_id = ? AND user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $product_id, $user_id);
            $stmt->execute();
        } else {
            // Check if the product exists in the cart
            $query = "SELECT id FROM cart WHERE product_id = ? AND user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $product_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update the quantity if product exists
                $query = "UPDATE cart SET quantity = ? WHERE product_id = ? AND user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iii", $quantity, $product_id, $user_id);
                $stmt->execute();
            } else {
                // Insert the product if not already in the cart
                $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iii", $user_id, $product_id, $quantity);
                $stmt->execute();
            }
        }

        // Recalculate the grand total
        $query = "
            SELECT SUM(p.price * c.quantity) AS grand_total
            FROM cart c
            INNER JOIN product p ON c.product_id = p.id
            WHERE c.user_id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $grand_total = $result->fetch_assoc()['grand_total'] ?? 0;

        echo json_encode(['success' => true, 'grandTotal' => $grand_total]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>