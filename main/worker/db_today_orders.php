<?php
define('MSmember', true);
require('../../include/dbconnect.php');

// Count today's total orders
$sql = "SELECT COUNT(*) AS today_orders 
        FROM orders 
        WHERE DATE(created_at) = CURDATE()";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$today_orders = $row['today_orders'];

// Prepare response
$response = [
    'today_orders' => $today_orders
];

echo json_encode($response);
?>