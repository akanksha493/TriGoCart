<?php
session_start(); // Start the session

// Include the database connection
require "dbconfig.php";


// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page
    header("Location: login.php");
    exit;
}


// Get the logged-in user's ID
$user_id = $_SESSION['id'];

// Handle removing an item from the cart
if (isset($_POST['remove_item'])) {
    $cart_id = intval($_POST['product_id']);
    $query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $cart_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        $query = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $cart_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        // Remove item if quantity is set to 0
        $query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Handle clearing the cart
if (isset($_POST['clear_cart'])) {
    $query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle placing an order
if (isset($_POST['place_order'])) {
    $query = "SELECT c.id, c.quantity, p.id AS product_id, p.price
              FROM cart c 
              JOIN product p ON c.product_id = p.id 
              WHERE c.user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cart_id, $quantity, $product_id, $price);

    $orderItems = [];
    $totalAmount = 0;

    while (mysqli_stmt_fetch($stmt)) {
        $totalAmount += $price * $quantity;
        $orderItems[] = ['product_id' => $product_id, 'quantity' => $quantity, 'price' => $price];
    }
    mysqli_stmt_close($stmt);

    if (!empty($orderItems)) {
        $orderDate = date('Y-m-d H:i:s');
        $status = 'Pending';

        // Insert the order into the orders table
        $query = "INSERT INTO orders (user_id, order_date, status, total_amount) 
                  VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "issd", $user_id, $orderDate, $status, $totalAmount);
        mysqli_stmt_execute($stmt);
        $order_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        // Insert order items into order_items table
        foreach ($orderItems as $item) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Clear the cart after placing the order
        $query = "DELETE FROM cart WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirect to order confirmation page
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit();
    }
}

// Fetch cart items for display
$sql = "SELECT c.id, c.quantity, p.name, p.price, (c.quantity * p.price) AS total 
        FROM cart c 
        JOIN product p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $cart_id, $quantity, $product_name, $price, $total);

$cartItems = [];
$grandTotal = 0;
while (mysqli_stmt_fetch($stmt)) {
    $cartItems[] = [
        'id' => $cart_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity,
        'total' => $total
    ];
    $grandTotal += $total;
}
mysqli_stmt_close($stmt);
?>


<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Your Cart</title>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
     <link rel="stylesheet" href="styles.css">
</head>

<body>
     <header>
          <?php include('header.php'); ?>
     </header>

     <div class="cart-container">
          <?php if (empty($cartItems)): ?>
          <p class="cart-empty-message">Your cart is empty!</p>
          <?php else: ?>
          <div class="cart-summary">
               <h3 id="grand-total">Total: ₹<?= number_format($grandTotal, 2); ?></h3>
               <form method="POST" action="" class="clearplace">
                    <button type="submit" name="clear_cart" class="btn-clear-cart">Clear Cart</button>
                    <button type="submit" name="place_order" class="btn-place-order">Place Order</button>
               </form>
          </div>
          <div class="cart-table-wrapper-out">
               <div class="cart-table-wrapper-in">
                    <table class="cart-table">
                         <tbody id="cart-items">
                              <?php 
                        $grandTotal = 0;
                        foreach ($cartItems as $productId => $item): 
                            $itemTotal = $item['price'] * $item['quantity'];
                            $grandTotal += $itemTotal;
                        ?>
                              <tr data-product-id="<?= $productId; ?>">
                                   <td class="cart-product-name"><?= htmlspecialchars($item['name']); ?></td>
                                   <td class="cart-product-price">₹<?= number_format($item['price'], 2); ?></td>
                                   <td class="cart-product-quantity">
                                        <div class="quantity-controls">
                                             <button class="quantity-btn minus-btn" data-action="decrease">-</button>
                                             <input type="text" class="cart-quantity-input"
                                                  value="<?= $item['quantity']; ?>" readonly>
                                             <button class="quantity-btn plus-btn" data-action="increase">+</button>
                                        </div>
                                   </td>
                                   <td class="cart-product-total">₹<?= number_format($itemTotal, 2); ?></td>
                              </tr>
                              <?php endforeach; ?>
                         </tbody>
                    </table>
               </div>
          </div>

          <?php endif; ?>
     </div>

     <script>
     document.addEventListener("DOMContentLoaded", function() {
          const cartItems = document.querySelectorAll("#cart-items tr");

          const updateGrandTotal = () => {
               let grandTotal = 0;
               document.querySelectorAll(".cart-product-total").forEach((totalCell) => {
                    const itemTotal = parseFloat(totalCell.textContent.replace(/₹|,/g, ""));
                    grandTotal += itemTotal;
               });
               document.getElementById("grand-total").textContent = `Total: ₹${grandTotal.toFixed(2)}`;
          };

          cartItems.forEach((row) => {
               const productId = row.dataset.productId;
               const minusBtn = row.querySelector(".minus-btn");
               const plusBtn = row.querySelector(".plus-btn");
               const quantityInput = row.querySelector(".cart-quantity-input");
               const productTotal = row.querySelector(".cart-product-total");
               const price = parseFloat(row.querySelector(".cart-product-price").textContent
                    .replace(/₹|,/g, ""));
               const grandTotalElem = document.getElementById("grand-total");

               const updateQuantity = async (action) => {
                    let quantity = parseInt(quantityInput.value);
                    if (action === "increase") quantity++;
                    if (action === "decrease") quantity--;

                    if (quantity <= 0) {
                         // Remove the item row if quantity is zero
                         row.remove();
                         const response = await fetch("update_cart.php", {
                              method: "POST",
                              headers: {
                                   "Content-Type": "application/json",
                              },
                              body: JSON.stringify({
                                   product_id: productId,
                                   quantity: 0,
                              }),
                         });
                         if (!response.ok) console.error("Failed to update cart");
                    } else {
                         // Update UI immediately
                         quantityInput.value = quantity;
                         const newTotal = quantity * price;
                         productTotal.textContent = `₹${newTotal.toFixed(2)}`;

                         // Send update to server using AJAX
                         const response = await fetch("update_cart.php", {
                              method: "POST",
                              headers: {
                                   "Content-Type": "application/json",
                              },
                              body: JSON.stringify({
                                   product_id: productId,
                                   quantity,
                              }),
                         });

                         if (!response.ok) {
                              console.error("Failed to update cart");
                         }
                    }

                    // Update grand total in the UI
                    updateGrandTotal();
               };

               minusBtn.addEventListener("click", () => updateQuantity("decrease"));
               plusBtn.addEventListener("click", () => updateQuantity("increase"));
          });

          // Initialize grand total on page load
          updateGrandTotal();
     });
     </script>


     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous">
     </script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous">
     </script>
     <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>

</html>