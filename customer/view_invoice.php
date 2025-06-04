<?php
include 'header.php';

$order_id = $_GET['order_id'] ?? 0;

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
        <link href="../css/print.css" rel="stylesheet" />
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
<section id="print-area" class="checkout-section">
<div class="container py-4 card">
  <div>
    <div class="py-4">
      <div class="px-14 py-2">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <div>
                  <img src="../assets/img/master_sushi.jpg" class="h-12" />
                </div>
              </td>

              <td class="align-top">
                <div class="text-sm">
                  <table class="border-collapse border-spacing-0">
                    <tbody>
                      <tr>
                        <td class="border-r pr-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Order Date</p>
                            <p class="whitespace-nowrap font-bold text-main text-right"><?= date('Y-m-d', strtotime($order['created_at'])) ?></p>
                          </div>
                        </td>
                        <td class="pl-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Invoice #</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">MSFK-2025-<?= $order['order_id'] ?></p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-slate-100 px-14 py-6 text-sm">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">Customer Details</p>
                  <p>Name:  <?= htmlspecialchars($order['customer_name']) ?></p>
                  <p>Email:  <?= htmlspecialchars($order['customer_email']) ?></p>
                  <p>Phone: <?= htmlspecialchars($order['customer_phone']) ?></p>
                </div>
              </td>
              <td class="w-1/2 align-top text-right">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">Deliver Details</p>
                  <p>Pickup Date:  <?= $order['pickup_date'] ?></p>
                  <p>Picup Time:  <?= $order['pickup_time'] ?></p>
                  <p>Status:  <?= $order['order_status'] ?></p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <table class="w-full border-collapse border-spacing-0">
          <thead>
            <tr>
              <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">Image</td>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Product Name</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Price</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Qty.</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Subtotal</td>
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
              <td class="border-b py-3 pl-2">
                    <?php if (!empty($item['image_path'])): ?>
                        <img src="../<?= htmlspecialchars($item['image_path']) ?>" alt="Product Image" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                    <?php else: ?>
                        <span>No image</span>
                    <?php endif; ?>
              </td>
              <td class="border-b py-3 pl-2"> <?= htmlspecialchars($item['product_name']) ?> </td>
              <td class="border-b py-3 pl-2 text-right">₱<?= number_format($item['price'], 2) ?></td>
              <td class="border-b py-3 pl-2 text-center"><?= $item['quantity'] ?></td>
              <td class="border-b py-3 pl-2 text-right">₱<?= number_format($subtotal, 2) ?></td>
            </tr>
                    <?php endwhile; ?>
            <tr>
              <td colspan="7">
                <table class="w-full border-collapse border-spacing-0">
                  <tbody>
                    <tr>
                      <td>
                        <table class="w-full border-collapse border-spacing-0">
                          <tbody>
                            <tr>
                              <td class="w-full"></td>
                              <td class="bg-main p-3">
                                <div class="whitespace-nowrap font-bold text-white">Total:</div>
                              </td>
                              <td class="bg-main p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-white">₱<?= number_format($grandTotal, 2) ?></div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 text-sm text-neutral-700">
        <p>Mode of Payment: <span class="text-main font-bold">GCASH</span> </p>
        <!-- <p>Bank/Sort Code: 1234567</p>
        <p>Account Number: 123456678</p>
        <p>Payment Reference: BRA-00335</p> -->
      </div>

      <!-- <div class="px-14 py-10 text-sm text-neutral-700">
        <p class="text-main font-bold">Notes</p>
        <p class="italic">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries
          for previewing layouts and visual mockups.</p>
      </div> -->
    </div>
    <div class="px-14 d-md-flex justify-content-md-between">
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
  const printContents = document.getElementById("print-area").innerHTML;
  const originalContents = document.body.innerHTML;

  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;

  // Optional: Reload scripts if needed (e.g., Bootstrap JS)
  location.reload();
}
</script>