<?php
define('MSmember', true); 
require('include/dbconnect.php'); 

// For guests
if (!isset($_SESSION['session_token'])) {
  $_SESSION['session_token'] = bin2hex(random_bytes(16));
}
$session_token = $_SESSION['session_token'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $stmt = $conn->prepare("SELECT * FROM guest_cart WHERE session_token = ? AND product_id = ?");
    $stmt->bind_param("si", $session_token, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stmt = $conn->prepare("UPDATE guest_cart SET quantity = quantity + ? WHERE session_token = ? AND product_id = ?");
        $stmt->bind_param("isi", $quantity, $session_token, $product_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO guest_cart (session_token, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $session_token, $product_id, $quantity);
        $stmt->execute();
  }
  header("Location: product_view.php?id=" . $product_id . "&added=true");
}

?>