<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid order ID.");
}

$order_id = intval($_GET['order_id']);

// Optional: Check if user is logged in or session matches
// You can add a user/session validation here

// 1. Check if the order exists and is still pending
$stmt = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();

if ($order['order_status'] !== 'Pending') {
    die("Only pending orders can be cancelled.");
}

// 2. Cancel the order
$cancel_stmt = $conn->prepare("UPDATE orders SET order_status = 'Cancelled', modified_at = NOW(), cancelled_date = NOW() WHERE order_id = ?");
$cancel_stmt->bind_param("i", $order_id);

if ($cancel_stmt->execute()) {
    header( "Refresh:1; url=order_list.php?cancelled=success");
    exit();
} else {
    die("Failed to cancel order. Please try again.");
}

?>