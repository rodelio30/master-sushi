<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

$query = "SELECT * FROM users WHERE role !='Admin' AND status ='Inactive' ORDER BY user_id DESC";
$result = $conn->query($query);

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode(['data' => $users]);
?>
