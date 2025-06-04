<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$userQuery = $conn->prepare("SELECT first_name, last_name, address, contact_number FROM users WHERE user_id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

// Fetch cart items with product info
$cartQuery = $conn->prepare("
  SELECT c.quantity, p.product_name, p.selling_price 
  FROM cart c 
  JOIN products p ON c.product_id = p.product_id 
  WHERE c.user_id = ?
");
$cartQuery->bind_param("i", $user_id);
$cartQuery->execute();
$cartResult = $cartQuery->get_result();

// Calculate total
$total = 0;
$cartItems = [];
while ($item = $cartResult->fetch_assoc()) {
  $item['line_total'] = $item['quantity'] * $item['selling_price'];
  $total += $item['line_total'];
  $cartItems[] = $item;
}

?>  
<style>
    .checkout-section {
    min-height: 90vh;
    background-color: #f8f9fa;
      background: url('../assets/img/bg-cta.png') no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
    padding: 60px 0;
  }
   
  .checkout-card {
    background-color: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }

  .cart-item {
    border-bottom: 1px solid #e9ecef;
    padding: 10px 0;
  }

  .cart-total {
    font-size: 1.2rem;
    font-weight: 600;
  }

  @media (max-width: 768px) {
    .card h5, .card p { font-size: 14px; }
  }

</style>
<body>

<?php
$nav_active = "";
include 'nav.php';
?>  
<section class="checkout-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="checkout-card">
          <h3 class="mb-4 text-center">🛒 Checkout</h3>

          <!-- Cart Items -->
          <div class="cart-items mb-4">
            <?php if (count($cartItems) > 0): ?>
              <?php foreach ($cartItems as $item): ?>
                <div class="cart-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                    <small>Quantity: <?= $item['quantity'] ?></small>
                  </div>
                  <span class="text-success">₱<?= number_format($item['line_total'], 2) ?></span>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted">Your cart is empty.</p>
            <?php endif; ?>
          </div>

          <!-- Total -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <span class="cart-total">Total:</span>
            <span class="cart-total">₱<?= number_format($total, 2) ?></span>
          </div>

          <!-- Checkout Form -->
          <form action="place_order.php" method="POST">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" name="total" value="<?= $total ?>">

            <div class="mb-3">
              <label for="fullname" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullname" name="fullname"
                     value="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>" required>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Delivery Address</label>
              <textarea class="form-control" id="address" name="address" rows="2" required><?= htmlspecialchars($user['address']) ?></textarea>
            </div>
            <div class="mb-3">
              <label for="payment" class="form-label">Payment Method</label>
              <select class="form-select" id="payment" name="payment_method" required>
                <option value="" selected disabled>-- Select --</option>
                <option value="cop">Cash on Pickup</option>
                <option value="gcash" disabled>Gcash</option>
                <!-- <option value="bank">Bank Transfer</option> -->
              </select>
            </div>
            <div class="mb-3">
              <label for="transaction_type" class="form-label">Transaction Type</label>
              <input type="text" class="form-control" id="transaction_type" name="transaction_type" value="Pick Up" placeholder="Pick Up" readonly>
              <!-- <select class="form-select" id="transaction_type" name="transaction_type" required>
                <option value="" disabled selected>-- Select --</option>
                <option value="pick_up">Pickup</option>
              </select> -->
            </div>

            <button type="submit" class="btn btn-danger w-100">Place Order</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>