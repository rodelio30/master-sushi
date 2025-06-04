<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

$result = mysqli_query($conn, "SELECT status FROM shop_status LIMIT 1");
$row = mysqli_fetch_assoc($result);

echo json_encode(['status' => $row['status']]); // returns 'open' or 'closed'
?>