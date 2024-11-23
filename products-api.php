<?php
 include('header.php');
require 'dbconfig.php'; // Include the database connection

$sql = "SELECT * FROM product WHERE category='Electronics'";
$result = mysqli_query($conn, $sql);

$products = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($products);

mysqli_close($conn);
?>
