<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

// Count today's new users
$sql = "SELECT COUNT(*) AS today_users 
        FROM users 
        WHERE DATE(created_at) = CURDATE()";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$today_users = $row['today_users'];

// Prepare response
$response = [
    'today_users' => $today_users
];

echo json_encode($response);
?>