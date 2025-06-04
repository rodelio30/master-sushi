<?php
include 'header.php';

$order_id = $_GET['order_id'] ?? 0;

// Get order items
$stmt = $conn->prepare("
  SELECT 
    oi.*, 
    p.product_name,
    (SELECT image_path FROM product_image WHERE product_id = p.product_id ORDER BY image_id ASC LIMIT 1) AS image_path
  FROM order_items oi
  JOIN products p ON oi.product_id = p.product_id
  WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Get order info
$order_query = $conn->prepare("SELECT order_tracker, order_status FROM orders WHERE order_id = ?");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order_data = $order_result->fetch_assoc();
$order_tracker = $order_data['order_tracker'] ?? 'N/A';
$order_status = $order_data['order_status'] ?? 'N/A';
?>  
<style>
    .checkout-section {
    min-height: 90vh;
    background-color: #f8f9fa;
    background: url('../assets/img/bg-cta.png') no-repeat center center/cover;
    display: flex;
    /* align-items: center;
    justify-content: center; */
    padding: 30px 13rem;
  }
   

</style>
<body>

<?php
$nav_active = "orders";
include 'nav.php';
?>  
<section class="checkout-section">
<div class="container py-4 card">
   <div class="d-flex justify-content-between align-items-center"> 
  <h4 class="mb-4">🧾 Order Items (Order ID: <?= htmlspecialchars($order_tracker) ?>) </h4>
  <p class="mb-4">
  <strong>Status:</strong>
  <span class="badge bg-<?= $order_status == 'Completed' ? 'success' : ($order_status == 'Cancelled' ? 'danger' : ($order_status == 'Processing' ? 'primary' : 'warning')) ?>">
    <?= $order_status ?>
  </span>
</p>
   </div> 
  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price (₱)</th>
            <th>Subtotal (₱)</th>
          </tr>
        </thead>
        <tbody>
          <?php $total = 0; while ($row = $result->fetch_assoc()): $total += $row['subtotal']; ?>
            <tr>
            <td class="text-center">
                <?php if (!empty($row['image_path'])): ?>
                    <img src="../<?= htmlspecialchars($row['image_path']) ?>" alt="Product Image" width="90" height="90" style="object-fit: cover; border-radius: 5px;">
                <?php else: ?>
                    <span>No image</span>
                <?php endif; ?>
            </td>
              <td><?= htmlspecialchars($row['product_name']) ?></td>
              <td><?= $row['quantity'] ?></td>
              <td><?= number_format($row['price'], 2) ?></td>
              <td><?= number_format($row['subtotal'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
          <tr>
            <td colspan="4" class="text-end fw-bold">Total:</td>
            <td class="fw-bold text-success">₱<?= number_format($total, 2) ?></td>
          </tr>
        </tbody>
      </table>
      <div class="d-md-flex justify-content-md-end">
        <a href="order_list.php" class="btn btn-secondary mt-3">← Back to Orders</a>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">No items found for this order.</div>
  <?php endif; ?>
</div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>