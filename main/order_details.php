<?php 
// Check Connections
include 'template/checker.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id'] ?? 0);
    // $status = $_POST['status'] ?? '';
    $order_status = $_POST['order_status'] ?? '';

    $allowed = ['Pending', 'Processing', 'Completed', 'Cancelled'];
    if (!$order_id || !in_array($order_status, $allowed)) {
        http_response_code(400);
        echo "Invalid data.";
        exit;
    }
  $order_id = $_POST['order_id'];
  $order_status = $_POST['order_status'];

  $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
  $stmt->bind_param("si", $order_status, $order_id);
  $stmt->execute();
  echo "<script>alert('Room Details Updated Successfully'); window.location.href = 'order_details.php?order_id=" . $order_id ."';</script>";

  // header("Location: order_details.php?order_id=" . $order_id);
  // exit();
}

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Mark related notifications as read
if (isset($_GET['notification_id'])) {
  $notification_id = intval($_GET['notification_id']);

  $update_stmt = $conn->prepare("UPDATE order_notifications SET status = 'Seen', modified_at = NOW() WHERE notification_id = ? AND status = 'New'");
  $update_stmt->bind_param("i", $notification_id);
  $update_stmt->execute();
  $update_stmt->close();
}


// Get order details
$order_sql = "SELECT * FROM orders WHERE order_id = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}

// Get order items
$item_sql = "SELECT oi.*, p.product_name FROM order_items oi
             JOIN products p ON oi.product_id = p.product_id
             WHERE oi.order_id = ?";
$item_stmt = $conn->prepare($item_sql);
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$item_result = $item_stmt->get_result();

$order_items = [];
while ($row = $item_result->fetch_assoc()) {
    $order_items[] = $row;
}


// Check if receipt exists
$order_receipt_exists = false;
$receipt_path = "";

$receipt_check = $conn->prepare("SELECT receipt_path FROM payment_receipts WHERE order_id = ? LIMIT 1");
$receipt_check->bind_param("i", $order_id);
$receipt_check->execute();
$receipt_result = $receipt_check->get_result();

if ($receipt_result->num_rows > 0) {
    $row = $receipt_result->fetch_assoc();
    $receipt_path = $row['receipt_path']; // e.g. "uploads/receipts/receipt_123.jpg"
    $order_receipt_exists = true;
}

?>
<?php include 'template/header.php'; ?>
<style>
  .card-body p {
    margin-bottom: 0;
  }
</style>
    <body class="sb-nav-fixed">
        <?php include 'template/topbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'template/sidebar.php'; ?>
            <div id="layoutSidenav_content">
            <main>
            <div class="container-fluid px-4">
        <h1 class="mt-4">Order  Details</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="orders.php">Orders</a></li>
            <li class="breadcrumb-item active">Order Details</li>
        </ol>


        <div class="card mb-4">
            <div class="card-header">
                      <div class="d-flex justify-content-between">
                        <div class="mt-1">
                <i class="fas fa-info-circle me-1"></i>
                Order Information
                        </div>

                      <div class="d-flex align-items-center gap-2">
                          <span class="fw-bold">Update Status:</span>
                          <?php
                            $status = $order['order_status'];
                          ?>
                          <!-- <select name="order_status" id="status" class="form-select form-select-sm w-auto"> -->
                          <select class="form-select form-select-sm status-dropdown w-auto" data-id="<?= $order_id ?>" <?= (!$order_receipt_exists) ? 'disabled': '';?>>
                              <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                              <option value="Processing" <?= $status == 'Processing' ? 'selected' : '' ?>>Processing</option>
                              <option value="Completed" <?= $status == 'Completed' ? 'selected' : '' ?>>Completed</option>
                              <option value="Cancelled" <?= $status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                          </select>
                          <!-- <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button> -->
                          <?php if($order_receipt_exists): ?>
                            <button 
                              class="btn btn-sm btn-success" 
                              data-bs-toggle="modal" 
                              data-bs-target="#receiptModal<?= $order_id ?>" 
                            >
                              View Receipt
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="receiptModal<?= $order_id ?>" tabindex="-1" aria-labelledby="receiptModalLabel<?= $order_id ?>" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="receiptModalLabel<?= $order_id ?>">Receipt</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body text-center">
                                    <?php if(file_exists($receipt_path)): ?>
                                      <img src="<?= htmlspecialchars($receipt_path) ?>" alt="Receipt Image" class="img-fluid rounded">
                                      <a href="<?= htmlspecialchars($receipt_path) ?>" download class="btn btn-sm btn-success mt-3">
                                        Download Receipt
                                      </a>
                                    <?php else: ?>
                                      <div class="text-danger">Receipt not found.</div>
                                    <?php endif; ?>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php endif; ?>
                          <?php if ($order['order_status'] === 'Completed'): ?>
                              <a href="order_invoice.php?order_id=<?=$order_id?>" class="btn btn-sm btn-primary">View Invoice</a>
                          <?php endif; ?>
                      </div>
                </div>
            </div>
        <div id="printableInvoice">
            <div class="card-body">
            <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
  <div class="row">
    <div class="col-4">
      <p><strong>Customer Name:</strong></p>
    </div>
    <div class="col-8">
      <p><?= htmlspecialchars($order['customer_name']) ?></p>
    </div>
  </div>

  <div class="row">
    <div class="col-4">
      <p><strong>Email:</strong></p>
    </div>
    <div class="col-8">
      <p><?= htmlspecialchars($order['customer_email']) ?></p>
    </div>
  </div>

  <div class="row">
    <div class="col-4">
      <p><strong>Phone:</strong></p>
    </div>
    <div class="col-8">
      <p><?= htmlspecialchars($order['customer_phone']) ?></p>
    </div>
  </div>

  <div class="row">
    <div class="col-4">
      <p><strong>Address:</strong></p>
    </div>
    <div class="col-8">
      <p><?= htmlspecialchars($order['customer_address']) ?></p>
    </div>
  </div>
</div>

           <!-- Right Column -->
<div class="col-md-6">
  <div class="row">
    <div class="col-4">
      <p><strong>ID:</strong></p>
    </div>
    <div class="col-8">
      <p><?= htmlspecialchars($order['order_tracker']) ?></p>
    </div>
  </div>

  <div class="row">
    <div class="col-4">
      <p><strong>Payment Method:</strong></p>
    </div>
    <div class="col-8">
      <p><?= htmlspecialchars($order['payment_method']) ?></p>
    </div>
  </div>

  <div class="row">
    <div class="col-4">
      <p><strong>Status:</strong></p>
    </div>
    <div class="col-8">
      <?php
        $status = $order['order_status'];
        $badgeClass = match($status) {
          'Pending' => 'bg-warning text-white',
          'Processing' => 'bg-primary text-white',
          'Completed' => 'bg-success text-white',
          'Cancelled' => 'bg-danger text-white',
          default => 'bg-secondary text-white',
        };
      ?>
      <span class="badge <?= $badgeClass ?>" style="font-size: 0.9rem; padding: 0.3em 1em; border-radius: 1rem;">
        <?= htmlspecialchars($status) ?>
      </span>
    </div>
  </div>

  <div class="row">
    <div class="col-4">
      <p><strong>Order Date:</strong></p>
    </div>
    <div class="col-8">
      <p><?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></p>
    </div>
  </div>
</div> 
        </div>
                <hr>

                <h5>Items Ordered:</h5>
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $subtotal = 0; ?>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td>₱<?= number_format($item['price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td class="text-end">₱<?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                            <?php $subtotal += $item['subtotal']; ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end"><strong>₱<?= number_format($subtotal, 2) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div> 
</main> 



<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on('change', '.status-dropdown', function () {
  const orderId = $(this).data('id');
  const newStatus = $(this).val();

  $.ajax({
    url: 'worker/update_order_status.php',
    type: 'POST',
    data: { order_id: orderId, status: newStatus },
    success: function (res) {
      alert(res);
      location.reload();
    },
    error: function (xhr) {
      alert(xhr.responseText || 'Failed to update order status.');
      location.reload();
    }
  });
});
</script>

<script>
// function printInvoice() {
//   const content = document.getElementById('printableInvoice').innerHTML;
//   const printWindow = window.open('', '', 'width=800,height=600');

//   printWindow.document.write(`
//     <html>
//       <head>
//         <title>Print Invoice</title>
//         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
//         <style>
//           body { padding: 20px; font-family: Arial, sans-serif; }
//         </style>
//       </head>
//       <body>
//         ${content}
//       </body>
//     </html>
//   `);

//   printWindow.document.close();

//   // Wait for the styles to load before printing
//   printWindow.onload = function () {
//     printWindow.focus();
//     printWindow.print();
//     printWindow.close();
//   };
// }
// </script>