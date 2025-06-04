<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

if ($_SESSION['user_role'] === 'Admin') {
    header("Location: main/index.php");
    exit;
} 
if ($_SESSION['user_role'] === 'Staff') {
    header("Location: main/index.php");
    exit;
} 
if (empty($_SESSION['user_id'])) {
    header("Location: ../include/signout.php");
    exit;
} 
$user_id = $_SESSION['user_id']; // After login or registration
$session_token = $_SESSION['session_token'] ?? null;

if ($session_token) {
    // Get guest cart items
    $stmt = $conn->prepare("SELECT product_id, quantity FROM guest_cart WHERE session_token = ?");
    $stmt->bind_param("s", $session_token);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];

        // Insert into user cart or update if exists
        $insert = $conn->prepare("
            INSERT INTO cart (user_id, product_id, quantity) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
        ");
        $insert->bind_param("iii", $user_id, $product_id, $quantity);
        $insert->execute();
    }

    // Delete guest cart
    $delete = $conn->prepare("DELETE FROM guest_cart WHERE session_token = ?");
    $delete->bind_param("s", $session_token);
    $delete->execute();

    // Optional: unset the guest token now that it's merged
    unset($_SESSION['session_token']);
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Master Sushi 2025" />
        <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; style-src 'self';"> -->
        <link rel="icon" type="image/jpg" sizes="16x16" href="../assets/img/master_sushi_circle.png">
        <meta name="author" content="Master Sushi 2025" />
        <title>Master Sushi</title>
        <link href="../css/styles.css" rel="stylesheet" />

        <link href="../css/public_custom.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.cdnfonts.com/css/luckiest-guy" rel="stylesheet">
</head>