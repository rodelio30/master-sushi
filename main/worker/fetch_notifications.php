<?php
define('MSmember', true);
require('../../include/dbconnect.php');

header('Content-Type: application/json');

// Fetch latest 5 notifications (you can adjust LIMIT)
$sql = "SELECT notification_id, message, order_id, status, created_at 
        FROM order_notifications 
        WHERE status = 'New'
        ORDER BY created_at DESC 
        LIMIT 5";

$result = $conn->query($sql);

$notifications = [];

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>
