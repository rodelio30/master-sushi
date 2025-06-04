<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$action = $_POST['action'];

if (!in_array($action, ['increase', 'decrease'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

// Get current quantity
$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $quantity = $row['quantity'];
    $new_qty = ($action == 'increase') ? $quantity + 1 : max(1, $quantity - 1);

    // Update quantity
    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $update->bind_param("iii", $new_qty, $user_id, $product_id);
    $update->execute();

    echo json_encode(['success' => true, 'new_qty' => $new_qty]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart.']);
}
