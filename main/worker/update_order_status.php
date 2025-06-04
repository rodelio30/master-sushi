<?php
define('MSmember', true);
require('../../include/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    $allowed = ['Pending', 'Processing', 'Completed', 'Cancelled'];
    if (!$order_id || !in_array($status, $allowed)) {
        http_response_code(400);
        echo "Invalid data.";
        exit;
    }

    // Fetch current status
    $currentStatusStmt = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ?");
    $currentStatusStmt->bind_param("i", $order_id);
    $currentStatusStmt->execute();
    $currentStatusResult = $currentStatusStmt->get_result();

    if ($currentStatusResult->num_rows === 0) {
        echo "Order not found.";
        exit;
    }

    $currentOrder = $currentStatusResult->fetch_assoc();
    $currentStatus = $currentOrder['order_status'];

    // Prevent changing status if already Completed or Cancelled
    if (in_array($currentStatus, ['Completed', 'Cancelled'])) {
        http_response_code(403);
        echo "Order status can no longer be updated.";
        exit;
    }

    // Update status
    $updateStmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $updateStmt->bind_param("si", $status, $order_id);
    $updateStmt->execute();

    // Reduce stock if status is Completed
    if ($status === 'Completed') {
        $itemStmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
        $itemStmt->bind_param("i", $order_id);
        $itemStmt->execute();
        $itemsResult = $itemStmt->get_result();

        while ($item = $itemsResult->fetch_assoc()) {
            $product_id = $item['product_id'];
            $ordered_quantity = $item['quantity'];

            $updateQtyStmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE product_id = ?");
            $updateQtyStmt->bind_param("ii", $ordered_quantity, $product_id);
            $updateQtyStmt->execute();
        }
        
        // Insert into sales
        $salesStmt = $conn->prepare("
        SELECT oi.order_id, oi.product_id, p.product_name, p.buying_price, p.selling_price, oi.quantity
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
        ");
        $salesStmt->bind_param("i", $order_id);
        $salesStmt->execute();
        $salesResult = $salesStmt->get_result();

        $insertSalesStmt = $conn->prepare("
        INSERT INTO sales (order_id, product_id, product_name, buying_price, selling_price, quantity)
        VALUES (?, ?, ?, ?, ?, ?)
        ");

        while ($sale = $salesResult->fetch_assoc()) {
            $insertSalesStmt->bind_param(
                "iisddi",
                $sale['order_id'],
                $sale['product_id'],
                $sale['product_name'],
                $sale['buying_price'],
                $sale['selling_price'],
                $sale['quantity']
            );
            $insertSalesStmt->execute();
        }
    }

    echo "Order Status Updated!";
}
?>
