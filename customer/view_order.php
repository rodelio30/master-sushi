<?php
include 'header.php';

$user_id = $_SESSION['user_id'];
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
$order_query = $conn->prepare("SELECT order_id, order_tracker, order_status, payment_method FROM orders WHERE order_id = ?");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order_data = $order_result->fetch_assoc();
$order_id = $order_data['order_id'] ?? 'N/A';
$order_tracker = $order_data['order_tracker'] ?? 'N/A';
$order_status = $order_data['order_status'] ?? 'N/A';
$order_payment_method = $order_data['payment_method'] ?? 'N/A';



// Check if receipt exists
$order_receipt_exists = false;
$receipt_check = $conn->prepare("SELECT id FROM payment_receipts WHERE user_id = ? AND order_id = ? LIMIT 1");
$receipt_check->bind_param("ii", $user_id, $order_id);
$receipt_check->execute();
$receipt_result = $receipt_check->get_result();

if ($receipt_result->num_rows > 0) {
  $order_receipt_exists = true;
}
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
   
  .status-badge {
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #fff;
}
.status-delivered { background-color: #198754; }
.status-processing { background-color: #ffc107; color: #212529; }
.status-cancelled { background-color: #dc3545; }
.status-pending { background-color: #6c757d; }

    .popup-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
/* .popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(128, 128, 128, 0.5); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
} */

.popup-box {
  background-color: white;
  border-radius: 10px;
  width: 90%;
  max-width: 400px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.popup-box h2 {
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 10px;
}

.popup-box p {
  font-size: 0.95rem;
  color: #444;
  margin-bottom: 20px;
}

/* .popup-box button {
  background: none;
  border: none;
  color: #ff5722;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
} */
</style>
<body>

<?php
$nav_active = "orders";
include 'nav.php';
?>  



<?php if ($order_status === 'Pending' && ($order_payment_method === 'Bank Transfer' || $order_payment_method === 'GCash' ) ) : ?>
  
  <?php if (!$order_receipt_exists): ?>
    <!-- Payment Pending Modal -->
    <div class="modal fade" id="paymentPopup" tabindex="-1" aria-labelledby="paymentPopupLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="paymentPopupLabel">Payment Pending</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Your order is pending payment. If you already have a receipt, please upload it now to avoid delays.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal" data-bs-dismiss="modal">
              I have receipt, Upload Now
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Receipt Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form action="upload_receipt.php" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="uploadModalLabel">Upload Payment Receipt</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
              <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
              <div class="mb-3">
                <label for="receipt" class="form-label">Select Receipt (Image or PDF)</label>
                <input type="file" name="receipt" id="receipt" class="form-control" accept="image/*,application/pdf" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success btn-sm">Upload</button>
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const paymentPopup = new bootstrap.Modal(document.getElementById('paymentPopup'));
        paymentPopup.show();
      });
    </script>

  <?php else: ?>
    <!-- Payment Processing Modal -->
    <div class="modal fade" id="paymentProcessPopup" tabindex="-1" aria-labelledby="paymentProcessPopupLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="paymentProcessPopupLabel">Payment Processing</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Please wait while we verify your payment. The order status will update once it's confirmed.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Ok</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const paymentProcessPopup = new bootstrap.Modal(document.getElementById('paymentProcessPopup'));
        paymentProcessPopup.show();
      });
    </script>

  <?php endif; ?>

<?php endif; ?>

<section class="checkout-section">
<div class="container py-4 card">
   <div class="d-flex justify-content-between align-items-center"> 
<h4 class="mb-4">🧾 Order Items (Order ID: <?= htmlspecialchars($order_tracker) ?>) </h4>
  <p class="mb-4">
  <?php if ($order_status === 'Pending'): ?>
    <a href="cancel_order.php?order_id=<?= $order_id ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel Order</a>
  <?php endif; ?>
  <strong>Status:</strong>
  <span class="status-badge status-<?= $order_status == 'Completed' ? 'delivered' : ($order_status == 'Cancelled' ? 'cancelled' : ($order_status == 'Processing' ? 'processing' : 'pending')) ?>">
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
      <div class="d-md-flex justify-content-md-between">
        <a href="order_list.php" class="btn btn-secondary btn-sm mt-3">← Back to Orders</a>

        <?php if($order_status == 'Completed') { ?>
        <a href="view_invoice.php?order_id=<?= $order_id ?>" class="btn btn-success btn-sm mt-3" target="_blank">View Invoice</a>
        <?php } ?>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">No items found for this order.</div>
  <?php endif; ?>

<?php if (!$order_receipt_exists): ?>
<div class="accordion mt-4" id="accordionPanelsStayOpenExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
        Bank Transfer
      </button>
    </h2>
    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
      <div class="accordion-body">
  <!-- Bank Details -->
        <div id="bankDetails" class="alert alert-light border mt-2">
          <strong>Bank Transfer Details:</strong><br>
          Bank Name: <strong>Security Bank</strong><br>
          Account Name: 
          <span id="bankName">master sushi food kiosk</span>
          <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('bankName')">Copy</button><br>
          Account Number: 
          <span id="bankAccount">0000030504940</span>
          <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('bankAccount')">Copy</button>
        </div>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
        GCash
      </button>
    </h2>
    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
      <div class="accordion-body">
<!-- GCash Details -->
<div id="gcashDetails" class="alert alert-light border">
  <div class="row">
    <div class="col-md-7">
      <strong>GCash Details:</strong><br>
      Account Name: 
      <span id="gcashName">Jennifer N</span>
      <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('gcashName')">Copy</button><br>
      Number: 
      <span id="gcashNumber">09613165793</span>
      <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('gcashNumber')">Copy</button>
    </div>
    <div class="col-md-5 text-center">
      <!-- <img src="../assets/img/qr-code-ms.jpeg" alt="GCash QR Code" class="img-fluid border rounded" style="max-height: 150px;">
      <div class="small mt-2">Scan QR to Pay</div> -->
        <div>
          <img src="../assets/img/qr-code-ms.jpeg" alt="GCash QR Code" class="img-fluid border rounded" style="max-height: 200px;">
        </div>
        <!-- Download button overlay -->
        <a href="../assets/img/qr-code-ms.jpeg" download="gcash_qr.png"
          class="btn btn-sm btn-success mt-2"
          title="Download QR">
          Download QR
        </a>
      <div class="text-center small mt-2">Scan QR to Pay</div>
    </div>
  </div>
</div>
      </div>
    </div>
  </div>
</div>
  <?php endif; ?>
</div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>

<script>
    function copyToClipboard(elementId) {
    const text = document.getElementById(elementId).textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
      alert("Copied: " + text);
    }).catch(err => {
      console.error("Failed to copy: ", err);
    });
  }
</script>