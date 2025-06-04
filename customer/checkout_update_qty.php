<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

header('Content-Type: application/json');


if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'error' => 'You need to login first.',
        'redirect' => 'login.php'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['error' => 'Invalid product or quantity.']);
    exit;
}

// Update quantity in cart
$updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
$updateStmt->bind_param("iii", $quantity, $user_id, $product_id);
if (!$updateStmt->execute()) {
    echo json_encode(['error' => 'Failed to update cart.']);
    exit;
}

// Get updated price for this item
$itemStmt = $conn->prepare("SELECT c.quantity, p.selling_price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? AND c.product_id = ?");
$itemStmt->bind_param("ii", $user_id, $product_id);
$itemStmt->execute();
$itemResult = $itemStmt->get_result();
$item = $itemResult->fetch_assoc();

if (!$item) {
    echo json_encode(['error' => 'Item not found in cart.']);
    exit;
}

$item_total = number_format($item['selling_price'] * $item['quantity'], 2);
$qtyItem = $item['quantity'];

// Get updated cart total
$totalStmt = $conn->prepare("SELECT SUM(c.quantity * p.selling_price) AS cart_total FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?");
$totalStmt->bind_param("i", $user_id);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$total = $totalResult->fetch_assoc();

$cart_total = number_format($total['cart_total'], 2);

echo json_encode([
    'item_total' => $item_total,
    'cart_total' => $cart_total,
    'qtyItem' => $qtyItem
]);
?>