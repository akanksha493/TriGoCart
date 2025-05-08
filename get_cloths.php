<?php
session_start(); // Start the session to handle cart data
require 'dbconfig.php'; // Include the database connection

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle "Add to Cart" action
if (isset($_POST['add_to_cart'])) {
    if (isset($_SESSION['id'])) {  // Ensure the user is logged in
        $userId = $_SESSION['id'];
        $productId = $_POST['product_id'];
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $productStock = $_POST['product_stock'];
        $quantity = 1; // Default quantity is 1 (you can modify this as needed)

        // Check if the product already exists in the cart for the user
        $checkCartQuery = "SELECT * FROM cart WHERE user_id = '$userId' AND product_id = '$productId'";
        $checkResult = mysqli_query($conn, $checkCartQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            // Product already in the cart, update quantity
            $updateQuery = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$userId' AND product_id = '$productId'";
            if (mysqli_query($conn, $updateQuery)) {
                
            } else {
                echo "Error updating cart: " . mysqli_error($conn);
            }
        } else {
            // Insert new product into the cart
            $insertQuery = "INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES ('$userId', '$productId', '$quantity', NOW())";
            if (mysqli_query($conn, $insertQuery)) {
                
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        header("Location: login.php");
        exit;
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
                
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            
        }
    } else {
        header("Location: login.php");
        exit;
    }
}

$pattern = "";
if(isset($_GET["search"])){
    $pattern = trim($_GET["search"]);
    $pattern = "%{$pattern}%";

    $sql = "SELECT * FROM product WHERE category='Cloths' AND (product.name LIKE ? OR product.description LIKE ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "ss", $pattern, $pattern);
    
}else{
    // Prepare the SQL statement
    $sql = "SELECT * FROM product WHERE category='Cloths'";
    $stmt = mysqli_prepare($conn, $sql);
}
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

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
if(isset($_GET["sortby"])){
    if($_GET["sortby"] == "price_asc"){
        array_multisort (array_column($products, 'price'), SORT_ASC, $products);
    }else if($_GET["sortby"] == "price_desc"){
        array_multisort (array_column($products, 'price'), SORT_DESC, $products);
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
     <title>Cloths</title>
     <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Electronics</title>
     <style>
    .card-img-top {
        width: 100%; /* Ensures the image fits the card width */
        height: 400px; /* Fixed height */
        object-fit: cover; /* Ensures the image scales to fit without distortion */
        border-radius: 5px; /* Optional: for rounded corners */
    }
</style>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
     <header>
          <?php include('header.php'); ?>
          <!-- Include the navbar here -->
     </header>
     <div class="filter">
          <form action="sort.php" method="get" id="filter-form">
               <div class="sub-filter-field">
                    <label for="sortby">Sort By:</label>
                    <select id="sortby" name="sortby" onchange = "this.form.submit()">
                         <option value=""
                         <?php if(!isset($_GET["sortby"])){echo "selected";}?>
                         >Select sort option----</option>
                         <option value="price_asc"
                         <?php if(isset($_GET["sortby"]) && $_GET["sortby"]=='price_asc'){echo "selected";}?>
                         >Price : Low to High</option>
                         <option value="price_desc"
                         <?php if(isset($_GET["sortby"]) && $_GET["sortby"]=='price_desc'){echo "selected";}?>
                         >Price : High to Low</option>
                    </select>
               </div>
               <input type="hidden" name="current_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
          </form>
     </div>
     <div class="gunjcont">
          <div class="row">
               <?php if (empty($products)): ?>
               <p>No products found in this category.</p>
               <?php else: ?>
               <?php foreach ($products as $product): ?>
               <div class="col-md-4 mb-4">
                    <div class="card" style="width: 18rem;">
                         <img src="./image2/<?= htmlspecialchars($product['name']); ?>.jpg" class="card-img-top">
                         <div class="card-body">
                              <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                              <p class="card-text"><?= htmlspecialchars($product['description']); ?></p>
                              <p class="card-text"><strong>Price:</strong>
                                   ₹<?= htmlspecialchars(number_format($product['price'], 2)); ?></p>
                              <p class="card-text"><strong>Stock:</strong>
                                   <?= htmlspecialchars($product['stock_quantity']); ?></p>
                              <div class="formflex">
                                   <!-- Add to Cart Form -->
                                   <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                        <input type="hidden" name="product_name"
                                             value="<?= htmlspecialchars($product['name']); ?>">
                                        <input type="hidden" name="product_price" value="<?= $product['price']; ?>">
                                        <input type="hidden" name="product_stock"
                                             value="<?= $product['stock_quantity']; ?>">
                                        <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add
                                             to Cart</button>
                                   </form>

                                   <!-- Add to Wishlist Form -->
                                   <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                        <button type="submit" name="add_to_wishlist" class="add-to-wishlist-btn"><svg
                                                  xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                  fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                                                  <path
                                                       d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1" />
                                             </svg></button>
                                   </form>
                              </div>
                         </div>
                    </div>
               </div>
               <?php endforeach; ?>
               <?php endif; ?>
          </div>
     </div>

     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous">
     </script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous">
     </script>
</body>

</html>