<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

$sql = "
    SELECT DATE(sale_date) AS sale_day, SUM(total) AS daily_total
    FROM sales
    GROUP BY sale_day
    ORDER BY sale_day ASC
    LIMIT 30
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'date' => date('M j', strtotime($row['sale_day'])),
        'total' => (float)$row['daily_total']
    ];
}

echo json_encode($data);
?>