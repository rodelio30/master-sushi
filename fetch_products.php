<?php
define('MSmember', true); 
require('include/dbconnect.php'); 

$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

$query = "
  SELECT p.*, 
         (SELECT image_path FROM product_image WHERE product_id = p.product_id ORDER BY image_id ASC LIMIT 1) AS first_image
  FROM products p
  WHERE p.status = 'Available'
";

if ($category_id > 0) {
  $query .= " AND p.category_id = $category_id";
}

$result = mysqli_query($conn, $query);
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
  $products[] = $row;
}

echo json_encode($products);
?>