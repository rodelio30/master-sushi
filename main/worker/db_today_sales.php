<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

// Calculate today's total sales
$sql = "SELECT IFNULL(SUM(total), 0) AS today_sales, COUNT(*) AS today_orders 
        FROM sales 
        WHERE DATE(sale_date) = CURDATE()";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$total_sales = $row['today_sales'];
$total_orders = $row['today_orders'];

// Prepare response
$response = [
    'total_sales' => number_format($total_sales, 2),
    'today_orders' => $total_orders
];

echo json_encode($response);
?>