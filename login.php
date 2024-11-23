<?php
ob_start(); // Start output buffering
require "dbconfig.php"; // Include the database connection

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate credentials
    $sql = "SELECT id, username, password, first_name, last_name FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $first_name, $last_name);
        if (mysqli_stmt_fetch($stmt)) {
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, start a new session
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                // Redirect to index.php after successful login
                header("location: index.php");
                exit;
            } else {
                // Display error if password is not valid
                $password_err = "The password you entered was not valid.";
            }
        }
    } else {
        // Display error if username doesn't exist
        $username_err = "No account found with that username.";
    }

    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
ob_end_flush(); // End output buffering and flush output
?>

<!doctype html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

     <link rel="stylesheet" href="projectstyles.css">
     <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
     <title>Login - HAGS</title>
     <style>
     body {
          background-color: #f8f9fa;
     }

     .forms {
          margin-top: 100px;
     }

     .form-content {
          background-color: #ffffff;
          padding: 30px;
          border-radius: 10px;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
     }

     .headerr {
          font-size: 24px;
          margin-bottom: 20px;
          text-align: center;
          font-weight: bold;
     }

     .input-field {
          margin-bottom: 15px;
     }

     .button-field {
          text-align: center;
     }

     .form-link {
          text-align: center;
          margin-top: 15px;
     }

     .btn-primary {
          width: 100%;
     }
     </style>
</head>

<body>
     <header>
          <?php include('header.php'); ?>
     </header>
     <div class="gunjanlogin">
          <div class="row justify-content-center gunjanddd">
               <div class="col-md-4">
                    <div class="form-content">
                         <div class="headerr">Login</div>
                         <form action="" method="post">
                              <div class="field input-field">
                                   <input type="text" name="username" placeholder="Username" class="input form-control"
                                        required>
                              </div>

                              <div class="field input-field">
                                   <input type="password" name="password" placeholder="Password"
                                        class="password form-control" required>
                              </div>

                              <div class="field button-field">
                                   <button type="submit" class="btn btn-primary">Login</button>
                              </div>
                         </form>
                         <div class="form-link">
                              <span>Don't have an account? <a href="register.php" class="link signup-link">Sign
                                        Up</a></span>
                         </div>
                    </div>
               </div>
          </div>
     </div>
     

     <!-- Optional JavaScript -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous">
     </script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous">
     </script>

</body>

</html>