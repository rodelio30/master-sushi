<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

$query = "SELECT id, ip_address, access_time FROM user_logs ORDER BY access_time DESC";
$result = mysqli_query($conn, $query);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id' => $row['id'],
        'ip_address' => $row['ip_address'],
        'access_time' => date('M d, Y h:i A', strtotime($row['access_time']))
    ];
}

echo json_encode(['data' => $data]);
?>
