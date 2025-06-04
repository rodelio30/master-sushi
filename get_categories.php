<?php
define('MSmember', true); 
require('include/dbconnect.php'); 

$query = "SELECT * FROM categories WHERE status = 'Active' ORDER BY category_name";
$result = mysqli_query($conn, $query);

$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

echo json_encode($categories);
?>