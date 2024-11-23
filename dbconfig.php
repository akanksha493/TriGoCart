<?php
    // Database credentials
    $host = 'localhost'; // Database host
    $user = 'root'; // Database username
    $pass = ''; // Database password
    $db = 'ecomm'; // Database name

    $conn = mysqli_connect($host, $user, $pass,$db);
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
?>