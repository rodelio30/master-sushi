<?php
// invoice.php
require 'db_conn.php'; // adjust if needed

if (!isset($_GET['order_id'])) {
    die('Order ID not provided.');
}

$order_id = intval($_GET['order_id']);

// Fetch order data
$orderSql = "SELECT * FROM orders WHERE id = ?";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("i", $order_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

if ($orderResult->num_rows === 0) {
    die('Order not found.');
}

$order = $orderResult->fetch_assoc();

// Fetch order items
$itemsSql = "SELECT * FROM order_items WHERE order_id = ?";
$itemsStmt = $conn->prepare($itemsSql);
$itemsStmt->bind_param("i", $order_id);
$itemsStmt->execute();
$itemsResult = $itemsStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $order['id'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h2>Invoice</h2>
    <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
    <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
    <p><strong>Order Date:</strong> <?= $order['created_at'] ?></p>
    <p><strong>Pickup Date:</strong> <?= $order['pickup_date'] ?></p>
    <p><strong>Pickup Time:</strong> <?= $order['pickup_time'] ?></p>

    <h4 class="mt-4">Order Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $grandTotal = 0;
        while ($item = $itemsResult->fetch_assoc()):
            $subtotal = $item['price'] * $item['quantity'];
            $grandTotal += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td>₱<?= number_format($subtotal, 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th>₱<?= number_format($grandTotal, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <button onclick="window.print()" class="btn btn-success no-print">Print</button>
    <a href="orders.php" class="btn btn-secondary no-print">Back</a>
</div>

</body>
</html>
