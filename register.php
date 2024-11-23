<?php
require "dbconfig.php";
$username = $password = $confirm_password = $first_name = $last_name  = $phone = $country = "";
$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err  = $phone_err = $country_err = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Check if first name is empty
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "First name cannot be blank";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Check if last name is empty
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Last name cannot be blank";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Check for email
    if (empty(trim($_POST["username"]))) {
        $username_err = "Username cannot be blank";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check for phone number
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Phone number cannot be blank";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Check for country
    if (empty(trim($_POST["country"]))) {
        $country_err = "Country cannot be blank";
    } else {
        $country = trim($_POST["country"]);
    }

    // Check for password
    if (empty(trim($_POST['CrPass']))) {
        $password_err = "Password cannot be blank";
    } elseif (strlen(trim($_POST['CrPass'])) < 5) {
        $password_err = "Password cannot be less than 5 characters";
    } else {
        $password = trim($_POST['CrPass']);
    }

    // Check for confirm password field
    if (trim($_POST['CrPass']) != trim($_POST['CnPass'])) {
        $password_err = "Passwords should match";
    }
    echo "First Name: $first_name, Last Name: $last_name, username: $username, Phone: $phone, Country: $country";
    // If there were no errors, go ahead and insert into the database
    if (empty($password_err) && empty($first_name_err) && empty($last_name_err) && empty($username_err) && empty($phone_err) && empty($country_err)) {
        $sql = "INSERT INTO users (first_name, last_name, username, password, phone, country) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
        } else {
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $username, $param_password, $phone, $country);
            //$param_password = password_hash($password, PASSWORD_DEFAULT);
            
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
                exit; // Ensure no further code is executed after the redirect
            } else {
                echo "Error executing query: " . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Close the connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     <title>Sign Up</title>
     <style>
     body {
          background-color: #f8f9fa;
          /* Light background color */
     }

     .forms {
          margin-top: 100px;
          /* Space from the top */
     }

     .form-content {
          background-color: #ffffff;
          /* White background for the form */
          padding: 30px;
          /* Padding inside the form */
          border-radius: 10px;
          /* Rounded corners */
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
          /* Subtle shadow */
     }

     .headerr {
          font-size: 24px;
          margin-bottom: 20px;
          text-align: center;
          font-weight: bold;
          /* Bold header */
     }

     .input-field {
          margin-bottom: 15px;
     }

     .button-field {
          text-align: center;
     }

     .form-link {
          text-align: center;
          /* Center align the link */
          margin-top: 15px;
          /* Space above the link */
     }

     .btn-primary {
          width: 100%;
          /* Full width button */
     }
     </style>
</head>

<body>
     <header>
          <?php include('header.php'); ?>
          <!-- Include the navbar here -->
     </header>
     <section class="gunjanlogin">
          <div class="row justify-content-center gunjanddd">
               <div class="col-md-6">
                    <div class="form-content">
                         <div class="headerr">REGISTER</div>
                         <form action="" method="post">
                              <!-- Change action to your PHP script -->
                              <div class="field input-field">
                                   <input type="text" placeholder="First Name" class="input form-control"
                                        name="first_name" required>
                              </div>

                              <div class="field input-field">
                                   <input type="text" placeholder="Last Name" class="input form-control"
                                        name="last_name" required>
                              </div>

                              <div class="field input-field">
                                   <input type="text" placeholder="Username" class="input form-control" name="username"
                                        required>

                              </div>

                              <div class="field input-field">
                                   <input type="password" placeholder="Create password" class="password form-control"
                                        name="CrPass" required>
                              </div>

                              <div class="field input-field">
                                   <input type="password" placeholder="Confirm password" class="password form-control"
                                        name="CnPass" required>
                                   <i class='bx bx-hide eye-icon'></i>
                              </div>

                              <div class="field input-field">
                                   <input type="tel" placeholder="Phone Number" class="input form-control" name="phone"
                                        required>
                              </div>

                              <div class="field input-field">
                                   <select class="input form-control" name="country" required>
                                        <option value="" disabled selected>Select Country</option>
                                        <option value="USA">USA</option>
                                        <option value="Canada">Canada</option>
                                        <option value="UK">UK</option>
                                        <option value="Australia">Australia</option>
                                        <option value="India">India</option>
                                        <!-- Add more countries as needed -->
                                   </select>
                              </div>

                              <div class="field button-field">
                                   <button type="submit" class="btn btn-primary">Register</button>
                              </div>
                         </form>
                         <div class="form-link">
                              <span>Already have an account? <a href="login.php"
                                        class="link login-link">Login</a></span>
                         </div>
                    </div>
               </div>
          </div>
     </section>

     <!-- Optional JavaScript -->
     <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
          integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
     </script>
     -