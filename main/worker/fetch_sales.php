<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 


header('Content-Type: application/json');

$sql = "SELECT 
            s.sale_id, 
            p.product_name, 
            s.quantity, 
            s.total, 
            s.sale_date
        FROM sales s
        JOIN products p ON s.product_id = p.product_id
        ORDER BY s.sale_date DESC";

$result = $conn->query($sql);
$sales = [];

while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
}

echo json_encode($sales);
?>