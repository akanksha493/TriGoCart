<?php
require('dbconfig.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>HAGS</title>
     <link rel="stylesheet" href="style.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <script src="https://kit.fontawesome.com/7f33e8970d.js" crossorigin="anonymous"></script>
</head>

<body>
     <nav class="navgunj">
          <!-- <nav class="navbar navbar-expand-lg navgunj"> -->
          <div class="gunjfluid">
               <div class="logo">
                    <div class="logo1">
                    </div>
               </div>
               <!-- Search bar -->
               <div style="display: flex; justify-content: center; height: 6vh; ">
               <?php
                    $current_page = basename($_SERVER['PHP_SELF']);
                    if ($current_page == 'get_books.php' || $current_page == 'get_electronics.php' || $current_page == 'get_cloths.php') :
               ?>
                    <form action="search.php" method="get" style="padding: 2.5vh 0vh; margin-left:2rem;">
                         <input id="search" type="text" name="search" placeholder="Search for products..." 
                         <?php if(isset($_GET["search"]) && !empty($_GET['search'])):?>
                              value = "<?php echo $_GET["search"] ?>"
                         <?php else:?>
                              value = ""
                         <?php endif;?>
                         />
                         <input type="hidden" name="current_url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
                         <button class="go-bttn" type="submit">Go</button>
                    </form>
               <?php endif;?>

               <!-- Navigation list -->
               <div class="navlist">
                    <div class=" navulgunj">
                         <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0 navulgunj"> -->
                         <div class="nav-item"><a href="index.php" style="text-decoration: none;">
                                   <div class="navdiv">Home</div>
                              </a></div>
                         <div class="nav-item"><a href="get_electronics.php" style="text-decoration: none;">
                                   <div class="navdiv">Electronics</div>
                              </a></div>
                         <div class="nav-item"><a href="get_cloths.php" style="text-decoration: none;">
                                   <div class="navdiv">Apparel</div>
                              </a></div>
                         <div class="nav-item"><a href="get_books.php" style="text-decoration: none;">
                                   <div class="navdiv">Books</div>
                              </a></div>
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
                                   <!-- style="display: flex; flex-direction: row;">
                          <div class="nav-item" style="display: flex; flex-direction: row;"> -->


                                   <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                                   <!-- <p class="nav-link"> -->
                                   <div class="navdiv">
                                        <i class="fa-regular fa-user icon" aria-hidden="true"></i>

                                        <!-- <p class="d-inline-block nav-link"> -->
                                        <?= htmlspecialchars($_SESSION['first_name']); ?>
                                   </div>
                                   <!-- </p> -->
                                   <!-- <a class="nav-link" href="register.php">
                                   <div class="navdiv">Register</div>
                              </a></li> -->
                         </div>
                         <div class="nav-item">

                              <a href="logout.php" style="text-decoration: none;">
                                   <div class="navdiv">Log out</div>
                              </a>
                              <?php else: ?>
                              <a href="login.php" style="text-decoration: none;">
                                   <div class="navdiv">Log in</div>
                              </a>
                              <?php endif; ?>

                              <!-- <a   href="register.php">
                                   <div class="navdiv">Register</div>
                              </a> -->

                         </div>
                    </div>



               </div>
     </nav>
</body>

</html>