<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Home</title>
     <link rel="stylesheet" href="style.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
     </script>
     <script src="https://kit.fontawesome.com/7f33e8970d.js" crossorigin="anonymous"></script>
     <style>
        .welcome-container { text-align: center; margin-top: 50px; /* Space from the top */ } .welcome-message { font-size: 24px; font-weight: bold; /* Bold message */
     </style>
</head>

</head>
<body>
    <!-- Navigation Pane -->
    <nav class="navbar navbar-expand-lg navgunj">
          <div class="container-fluid">
               <div class="logo">
                    <h1>AGS</h1>
               </div>
               <form class="d-flex" action="search.php" method="get">
                    <input class="form-control me-2" type="search" name="search" id="search" placeholder="Search here"
                         aria-label="Search">
                    <button class="btn btn-outline-success" type="submit" style="color: hsl(28, 88%, 62%);">Go</button>
               </form>
               <div class="navlist">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 navulgunj">
                         <li class="nav-item"><a class="nav-link" href="index.php">
                                   <div class="navdiv">Home</div>
                              </a>
                         </li>
                         <li class=" nav-item">
                              <a class="nav-link" href="get_electronics.php">
                                   <div class="navdiv">Electronics</div>
                              </a>
                         </li>
                         <li class="nav-item"><a class="nav-link" href="get_cloths.php">
                                   <div class="navdiv">Apparel</div>
                              </a></li>
                         <li class="nav-item"><a class="nav-link" href="get_books.php">
                                   <div class="navdiv">Books</div>
                              </a></li>
                         <li class="nav-item"><a class="nav-link" href="register.php">
                                   <div class="navdiv">Register</div>
                              </a></li>
                         <li class="nav-item"><a class="nav-link" href="cart.php">
                                   <div class="navdiv">Cart</div>
                              </a>
                         </li>
                    </ul>
               </div>

               <div class="user-profile login">
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <p class="d-inline-block" style="font-size: 12px;">
                         <?= htmlspecialchars($_SESSION['firstname']) . ' ' . htmlspecialchars($_SESSION['lastname']); ?>
                    </p>
                    <a class="logdiva" href="logout.php">
                         <div class="logdiv">Log in</div>
                    </a>

                    <?php else: ?>
                    <a class="logdiva" href="login.php">
                         <div class="logdiv">Log out</output></div>
                    </a>
                    <?php endif; ?>
               </div>
          </div>
     </nav>

    <div class="container welcome-container">
        <div class="welcome-message">
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaH2kHn6M6t9t5GA6d4STgVoC" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
</body>
</html>
