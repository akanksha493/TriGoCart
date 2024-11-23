<?php
require('dbconfig.php');
session_start(); // Start the session if not already started

?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Home</title>
     <link rel="stylesheet" href="style.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <script src="https://kit.fontawesome.com/7f33e8970d.js" crossorigin="anonymous"></script>
</head>

<body>
     <header>
          <?php include('header.php'); ?>
     </header>

     <div class="maincontent1">
          <div class="text">
               <div class="textbox">
                    <div class="text1">
                         <h1>Select The Best</h1>
                    </div>
                    <div class="text1">
                         <h1>Quality Products</h1>
                    </div>
                    <a href="" class="btn1" style="text-decoration: none;">
                         <div class="but">Shop Now
                         </div>
                    </a>
               </div>
          </div>
          <!-- <div class="pict"></div> -->

     </div>

     <!-- <footer>
          <p>&copy; 2024 Random company name. All rights reserved.</p>
     </footer> -->
</body>


</html>





<!-- <div class="maincontent1">
          <div class="maincontent">
               <div class="custom-carousel-container">
                    <div class="carousel-container">
                         <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                              <div class="carousel-inner">
                                   <div class="carousel-item active">
                                        <img src="./images/electronics-category.jpg" class="d-block w-100"
                                             alt="Electronics">
                                   </div>
                                   <div class="carousel-item">
                                        <img src="./images/books-category.jpg" class="d-block w-100" alt="Books">
                                   </div>
                                   <div class="carousel-item">
                                        <img src="./images/cloths-category.jpg" class="d-block w-100" alt="Cloths">
                                   </div>
                              </div>
                              <button class="carousel-control-prev" type="button"
                                   data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                   <span class="visually-hidden">Previous</span>
                              </button>
                              <button class="carousel-control-next" type="button"
                                   data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                   <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                   <span class="visually-hidden">Next</span>
                              </button>
                         </div>
                    </div>
               </div>
               <div class="outer-category-container">
                    <a href="get_electronics.php">
                         <div class="category-container" id="electronics">
                              <span class="category-name">Electronics</span>
                         </div>
                    </a>
                    <a href="get_cloths.php">
                         <div class="category-container" id="cloths">
                              <span class="category-name">Cloths</span>
                         </div>
                    </a>
                    <a href="get_books.php">
                         <div class="category-container" id="books">
                              <span class="category-name">Books</span>
                         </div>
                    </a>
               </div>
          </div>
     </div> -->

<!-- <footer>
          <p>&copy; 2024 Random company name. All rights reserved.</p>
     </footer> -->
<!-- <li class="nav-item">
                              <div class="nav-link" href="index.php">Home</div>
                         </li>
                         <li class=" nav-item">
                              <div class="nav-link" href="get_electronics.php">Electronics</div>
                         </li>
                         <li class="nav-item">
                              <div class="nav-link" href="get_cloths.php">Cloths</div>
                         </li>
                         <li class="nav-item">
                              <div class="nav-link" href="get_books.php">Books</div>
                         </li>
                         <li class="nav-item">
                              <div class="nav-link" href="register.php">Register</div>
                         </li>
                         <li class="nav-item">
                              <div class="nav-link" href="cart.php">Cart</div>
                         </li> -->
<!-- 


                         <li class="nav-item"><a class="nav-link" href="index.php">Home</a>
                         </li>
                         <li class=" nav-item"><div class="nav-link" href="get_electronics.php">Electronics</a></li>
                         <li class="nav-item"><a class="nav-link" href="get_cloths.php">Cloths</a></li>
                         <li class="nav-item"><a class="nav-link" href="get_books.php">Books</a></li>
                         <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                         <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a>
                         </li> -->