<?php
define('MSmember', true);
require('../../include/dbconnect.php');

header('Content-Type: application/json');

$from = $_POST['from'] ?? '';
$to = $_POST['to'] ?? '';

if (!$from || !$to) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT s.sale_id, s.sale_date, s.quantity, s.buying_price, s.selling_price, p.product_name 
    FROM sales s
    JOIN products p ON s.product_id = p.product_id
    WHERE DATE(s.sale_date) BETWEEN ? AND ?
    ORDER BY s.sale_date DESC
");
$stmt->bind_param("ss", $from, $to);
$stmt->execute();

$result = $stmt->get_result();
$sales = [];

while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
}

echo json_encode($sales);
?>