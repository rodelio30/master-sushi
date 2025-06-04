<?php
define('MSmember', true); 
require('include/dbconnect.php'); 

$category_id = $_POST['category_id'];

$query = "
    SELECT p.*, 
           (SELECT image_path FROM product_image WHERE product_id = p.product_id ORDER BY image_id ASC LIMIT 1) AS first_image
    FROM products p 
    WHERE p.category_id = '$category_id' AND p.status = 'Available'
";

$result = mysqli_query($conn, $query);
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>