<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Update quantity in cart
$updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
$updateStmt->bind_param("iii", $quantity, $user_id, $product_id);
$updateStmt->execute();

// Get updated item price
$itemStmt = $conn->prepare("SELECT c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? AND c.product_id = ?");
$itemStmt->bind_param("ii", $user_id, $product_id);
$itemStmt->execute();
$itemResult = $itemStmt->get_result();
$item = $itemResult->fetch_assoc();

$item_total = number_format($item['price'] * $item['quantity'], 2);

// Get updated cart total
$totalStmt = $conn->prepare("SELECT SUM(c.quantity * p.price) AS cart_total FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?");
$totalStmt->bind_param("i", $user_id);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$total = $totalResult->fetch_assoc();

$cart_total = number_format($total['cart_total'], 2);

echo json_encode([
    'item_total' => $item_total,
    'cart_total' => $cart_total
]);
?>