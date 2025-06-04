<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

header('Content-Type: application/json');

$sql = "SELECT order_id, customer_name, customer_email, customer_phone, total_amount, payment_method, order_status FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>