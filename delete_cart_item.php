<?php
define('MSmember', true); 
require('include/dbconnect.php'); 

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$product_id = intval($data['product_id'] ?? 0);

// Ensure session token exists
if (!isset($_SESSION['session_token'])) {
  echo json_encode(['success' => false, 'message' => 'No session token']);
  exit;
}

$session_token = $_SESSION['session_token'];

$stmt = $conn->prepare("DELETE FROM guest_cart WHERE session_token = ? AND product_id = ?");
$stmt->bind_param("si", $session_token, $product_id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
?>