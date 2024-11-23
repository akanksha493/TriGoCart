<?php
session_start(); // Start session to use cart functionality
include('header.php'); // Include the header

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle removing an item from the cart
if (isset($_POST['remove_item'])) {
    $productId = $_POST['product_id'];
    unset($_SESSION['cart'][$productId]);
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$productId]);
    }
}

// Handle clearing the cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch cart items
$cartItems = $_SESSION['cart'];
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
          <div class="cart-table-wrapper-out">
               <div class="cart-table-wrapper-in">
                    <table class="cart-table">
                         <!-- <thead>
                         <tr>
                              <th>Product</th>
                              <th>Price</th>
                              <th>Quantity</th>
                              <th>Total</th>
                              <th>Actions</th>
                         </tr>
                    </thead> -->
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
                                             <button class="quantity-btn minus-btn" data-action="decrease"> <svg
                                                       xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                       fill="currentColor" class="bi bi-dash-lg" viewBox="0 0 16 16">
                                                       <path fill-rule="evenodd"
                                                            d="M2 8a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 8" />
                                                  </svg></button>
                                             <input type="text" class="cart-quantity-input"
                                                  value="<?= $item['quantity']; ?>" readonly>
                                             <button class="quantity-btn plus-btn" data-action="increase"><svg
                                                       xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                       fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                       <path fill-rule="evenodd"
                                                            d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                  </svg></i></i></button>
                                        </div>
                                   </td>
                                   <td class="cart-product-total">₹<?= number_format($itemTotal, 2); ?></td>
                                   <!-- <td class="cart-product-actions">
                                   <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?= $productId; ?>">
                                        <button type="submit" name="remove_item" class="btn-remove">Remove</button>
                                   </form>
                              </td> -->
                              </tr>
                              <?php endforeach; ?>
                         </tbody>
                    </table>
               </div>
          </div>
          <div class="cart-summary">
               <h3 id="grand-total">Total: ₹<?= number_format($grandTotal, 2); ?></h3>
               <form method="POST" action="">
                    <button type="submit" name="clear_cart" class="btn-clear-cart">Clear Cart</button>
               </form>
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