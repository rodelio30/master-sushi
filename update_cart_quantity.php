<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');



define('MSmember', true); 
require('include/dbconnect.php'); 


if (!isset($_SESSION['session_token'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No session']);
    exit;
}

$session_token = $_SESSION['session_token'];

if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity < 1) $quantity = 1; // Minimum of 1

    // Update quantity in guest_cart
    $stmt = $conn->prepare("UPDATE guest_cart SET quantity = ? WHERE product_id = ? AND session_token = ?");
    $stmt->bind_param("iis", $quantity, $product_id, $session_token);
    if ($stmt->execute()) {
        // Fetch updated total for the item
        $query = $conn->prepare("SELECT quantity, p.selling_price FROM guest_cart gc JOIN products p ON gc.product_id = p.product_id WHERE gc.product_id = ? AND gc.session_token = ?");
        $query->bind_param("is", $product_id, $session_token);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        $total_price = $result['quantity'] * $result['selling_price'];

        echo json_encode(['success' => true, 'total_price' => number_format($total_price, 2)]);
    } else {
        echo json_encode(['success' => false, 'error' => 'DB error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
}
?>