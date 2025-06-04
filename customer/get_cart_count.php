<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

$user_id = $_SESSION['user_id']; 

$sql = "SELECT COUNT(user_id) AS total FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode(['count' => $data['total'] ?? 0]);
?>