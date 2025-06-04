<?php
include 'header.php';

$order_id = intval($_GET['order_id']);

// Fetch order data
$orderSql = "SELECT * FROM orders WHERE order_id = ?";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("i", $order_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

if ($orderResult->num_rows === 0) {
    die('Order not found.');
}

$order = $orderResult->fetch_assoc();

// Fetch order items
// $itemsSql = "SELECT * FROM order_items WHERE order_id = ?";
$itemsSql = "
  SELECT 
    oi.*, 
    p.product_name,
    (SELECT image_path FROM product_image WHERE product_id = p.product_id ORDER BY image_id ASC LIMIT 1) AS image_path
  FROM order_items oi
  JOIN products p ON oi.product_id = p.product_id
  WHERE oi.order_id = ?
";
$itemsStmt = $conn->prepare($itemsSql);
$itemsStmt->bind_param("i", $order_id);
$itemsStmt->execute();
$itemsResult = $itemsStmt->get_result();
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
  @media print {
  h2 {
    font-size: 20px;
  }

  img {
    max-height: 60px;
  }

  .invoice-header,
    .row.d-md-flex {
      page-break-inside: avoid;
    }

     .invoice-details {
    page-break-inside: avoid;
    margin-bottom: 20px;
  }

  .invoice-details small {
    font-size: 12px;
  }

  .badge.bg-secondary {
    background-color: #6c757d !important;
    color: #fff !important;
  }

  .text-md-end {
    text-align: right !important;
  }

    .invoice-details {
      display: flex !important;
      justify-content: space-between !important;
      flex-direction: row !important;
    }

    .invoice-details .left,
    .invoice-details .right {
      width: 48%;
    }

    .invoice-details .right {
      text-align: right;
    }

  }

.border-bottom {
  border-bottom: 2px solid #000 !important;
}

.fw-bold {
  font-weight: 700;
}
   

</style>
<body>

<?php
$nav_active = "orders";
include 'nav.php';
?>  
<section id="print-area" class="checkout-section">
<div class="container py-4 card">
    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
      <img src="../assets/img/master_sushi.jpg" alt="master sushi logo" style="width: 60px; height: auto; margin-right: 15px;">
      <h2 class="mb-0 fw-bold">Order Invoice</h2>
    </div>
<!-- <div class="row d-md-flex invoice-details d-flex flex-md-row"> -->
<div class="row invoice-details d-flex flex-md-row">
  <div class="col-md-6 left mb-3">
    <small class="d-block mb-2">
      <strong>Invoice ID:</strong> 
      <span class="badge bg-secondary">MSFK-2025-<?= $order['order_id'] ?></span>
    </small>
    <small class="d-block mb-2"><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></small>
    <small class="d-block mb-2"><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></small>
    <small class="d-block"><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></small>
  </div>
  <div class="col-md-6 right text-md-end">
    <small class="d-block mb-2"><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']) ?></small>
    <small class="d-block mb-2"><strong>Order Date:</strong> <?= $order['created_at'] ?></small>
    <small class="d-block mb-2"><strong>Pickup Date:</strong> <?= $order['pickup_date'] ?></small>
    <small class="d-block"><strong>Pickup Time:</strong> <?= $order['pickup_time'] ?></small>
  </div>
</div>

    <h4 class="mt-4">Order Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
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
                <td class="text-center">
                  <?php if (!empty($item['image_path'])): ?>
                      <img src="../<?= htmlspecialchars($item['image_path']) ?>" alt="Product Image" width="auto" height="80" style="object-fit: cover; border-radius: 5px;">
                  <?php else: ?>
                      <span>No image</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?= htmlspecialchars($item['product_name']) ?>
                </td>
                <td><?= $item['quantity'] ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td>₱<?= number_format($subtotal, 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total</th>
                <th>₱<?= number_format($grandTotal, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="d-md-flex justify-content-md-between">
      <a href="view_order.php?order_id=<?= $order_id ?>" class="btn btn-secondary btn-sm no-print">Back</a>
      <button onclick="printInvoice()" class="btn btn-success btn-sm no-print">Print</button>
    </div>
</div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>

<script>
function printInvoice() {
  const invoiceContent = document.getElementById("print-area").innerHTML;

  const printWindow = window.open('', '', 'width=900,height=650');
  printWindow.document.write(`
    <html>
    <head>
      <title>Print Invoice</title>
      <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
      <style>
        body {
          padding: 20px;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
          max-width: 900px;
          margin: auto;
        }
        img {
          max-width: 100px;
        }

        table {
          width: 100%;
          border-collapse: collapse;
        }
        table, th, td {
          border: 1px solid #dee2e6;
        }
        th, td {
          padding: 8px;
          text-align: center;
        }
        h2, h4 {
          margin-top: 20px;
        }
        @media print {
          .no-print {
            display: none;
          }
        }
      </style>
    </head>
    <body onload="window.print(); window.close();">
      <div class="container">
        ${invoiceContent}
      </div>
    </body>
    </html>
  `);
  printWindow.document.close();
}
</script>
