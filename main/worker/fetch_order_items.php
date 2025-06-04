<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

$order_id = $_GET['order_id'] ?? 0;
$order_id = intval($order_id);

if (!$order_id) {
  echo "<p>Invalid order ID.</p>";
  exit;
}


// First, fetch the order status
$status_stmt = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ?");
$status_stmt->bind_param("i", $order_id);
$status_stmt->execute();
$status_result = $status_stmt->get_result();

if ($status_result->num_rows === 0) {
  echo "<p>Order not found.</p>";
  exit;
}

$status_row = $status_result->fetch_assoc();
$order_status = htmlspecialchars($status_row['order_status']); // optional sanitize

// Define the badge class based on the status
switch (strtolower($order_status)) {
    case 'pending':
        $badge_class = 'text-bg-warning';
        break;
    case 'processing':
        $badge_class = 'text-bg-info';
        break;
    case 'completed':
        $badge_class = 'text-bg-success';
        break;
    case 'cancelled':
    case 'canceled':
        $badge_class = 'text-bg-danger';
        break;
    default:
        $badge_class = 'text-bg-secondary'; // fallback
        break;
}

echo '<h2>Order Status: <span class="badge rounded-pill ' . $badge_class . '">' . $order_status . '</span></h2>';

// Fetch items with image
$stmt = $conn->prepare("
  SELECT oi.quantity, oi.price, p.product_name, pi.image_path
  FROM order_items oi
  JOIN products p ON oi.product_id = p.product_id
  LEFT JOIN product_image pi ON oi.product_id = pi.product_id
  WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "<p>No items found for this order.</p>";
  exit;
}

echo '<div class="table-responsive">';
echo '<table class="table table-bordered">';
echo '<thead><tr><th>Image</th><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>';

while ($row = $result->fetch_assoc()) {
  $img = $row['image_path'] ? '../' . $row['image_path'] : 'no-image.jpg';
  $subtotal = $row['price'] * $row['quantity'];
  echo "<tr>
          <td><img src='$img' alt='Product Image' style='width: 60px; height: 60px; object-fit: cover;'></td>
          <td>{$row['product_name']}</td>
          <td>{$row['quantity']}</td>
          <td>₱" . number_format($row['price'], 2) . "</td>
          <td>₱" . number_format($subtotal, 2) . "</td>
        </tr>";
}
echo '</tbody></table>';
echo '</div>';
