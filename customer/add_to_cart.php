<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

$user_id = $_SESSION['user_id']; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("si", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("isi", $quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $user_id, $product_id, $quantity);
        $stmt->execute();
  }
  header("Location: product_view.php?id=" . $product_id . "&added=true");
}

?>