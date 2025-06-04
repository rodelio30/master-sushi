<?php
define('MSmember', true); 
require('../include/dbconnect.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['receipt'])) {
    $user_id = $_POST['user_id'];
    $order_id = $_POST['order_id'];
    $receipt = $_FILES['receipt'];

    $targetDir = "../assets/uploads/receipts/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $filename = uniqid() . "_" . basename($receipt["name"]);
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($receipt["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO payment_receipts (user_id, order_id, receipt_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $order_id, $targetFile);

        if ($stmt->execute()) {
            echo "<script>alert('Receipt uploaded successfully!'); window.location.href='view_order.php?order_id=$order_id';</script>";
        } else {
            echo "Database error: " . $stmt->error;
        }
    } else {
        echo "Upload failed.";
    }
} else {
    echo "Invalid request.";
}

?>