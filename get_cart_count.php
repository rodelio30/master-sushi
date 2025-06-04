<?php
define('MSmember', true); 
require('include/dbconnect.php'); 

if (!isset($_SESSION['session_token'])) {
    $_SESSION['session_token'] = bin2hex(random_bytes(32));
}
$session_token = $_SESSION['session_token'];

// $sql = "SELECT SUM(quantity) AS total FROM guest_cart WHERE session_token = ?";
$sql = "SELECT product_id, COUNT(session_token) AS total FROM guest_cart WHERE session_token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_token);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode(['count' => $data['total'] ?? 0]);
?>