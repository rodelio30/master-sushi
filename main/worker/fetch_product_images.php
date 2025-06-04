<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

$product_id = $_GET['product_id'];

$stmt = $conn->prepare("SELECT image_path FROM product_image WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_path'];
}

echo json_encode($images);
?>