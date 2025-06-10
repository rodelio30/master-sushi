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
                // ✅ EMAIL SENDING
    $userQuery = $conn->prepare("SELECT email, first_name, last_name FROM users WHERE user_id = ?");
    $userQuery->bind_param("i", $user_id);
    $userQuery->execute();
    $userResult = $userQuery->get_result();
    $userData = $userResult->fetch_assoc();

    $customer_email = $userData['email'];
    $customer_name  = $userData['first_name'] . ' ' . $userData['last_name'];

    $to_receiver = "mastersushifs@gmail.com";
    $subject = "Receipt Uploaded for Order #$order_id";
    $headers = $customer_email;

    $message = "
        <div style='font-family: Arial, sans-serif; padding: 20px;'>
            <h2 style='color: #333;'>New Receipt Upload Notification</h2>
            <p><strong>Customer:</strong> {$customer_name}</p>
            <p><strong>Order ID:</strong> {$order_id}</p>
            <p><strong>Email:</strong> {$customer_email}</p>
            <p><strong>Upload Time:</strong> " . date("F j, Y, g:i a") . "</p>
            <p>The customer has uploaded a receipt. You may review it in the admin panel.</p>
            <p><a href='https://mastersushiph.com/assets/uploads/receipts/{$filename}' target='_blank'>View Uploaded Receipt</a></p>
        </div>
    ";

    include '../mail/send_email.php';
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