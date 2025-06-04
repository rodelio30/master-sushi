<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

// $sql = "SELECT p.*, c.category_name FROM products p 
//         JOIN categories c ON p.category_id = c.category_id";
// $sql = "SELECT 
//         p.*, 
//         c.category_name,
//         (
//           SELECT image_path 
//           FROM product_image 
//           WHERE product_id = p.product_id 
//           ORDER BY image_id ASC 
//           LIMIT 1
//         ) AS product_image
//       FROM products p 
//       JOIN categories c ON p.category_id = c.category_id";
// $result = mysqli_query($conn, $sql);
// $product = [];
// while ($row = mysqli_fetch_assoc($result)) {
//     $product[] = $row;
// }
// echo json_encode(['data' => $product]);

$sql = "SELECT p.*, c.category_name 
        FROM products p
        JOIN categories c ON p.category_id = c.category_id";
$result = $conn->query($sql);

$products = [];

while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];

    // Fetch images for the current product
    $images = [];
    $imgQuery = $conn->prepare("SELECT image_path FROM product_image WHERE product_id = ?");
    $imgQuery->bind_param("i", $product_id);
    $imgQuery->execute();
    $imgResult = $imgQuery->get_result();
    while ($imgRow = $imgResult->fetch_assoc()) {
        $images[] = $imgRow['image_path'];
    }

    $imgQuery->close();

    $row['images'] = $images; // add all images to product
    $row['product_image'] = $images[0] ?? ''; // for display thumbnail
    $products[] = $row;
}

echo json_encode(['data' => $products]);
$conn->close();
?>