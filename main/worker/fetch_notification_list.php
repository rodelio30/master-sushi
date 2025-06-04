<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 


$sql = "SELECT 
            n.notification_id,
            n.message,
            n.order_id,
            n.status,
            n.created_at,
            o.order_tracker,
            o.order_status,
            CONCAT(u.first_name, ' ', u.last_name) AS full_name
        FROM order_notifications n
        INNER JOIN orders o ON n.order_id = o.order_id
        LEFT JOIN users u ON o.user_id = u.user_id
        ORDER BY n.created_at DESC";

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode(["status" => "success", "data" => $data]);
?>
