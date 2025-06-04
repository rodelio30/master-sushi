<?php
include 'header.php';

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?>  
<style>
    .checkout-section {
    min-height: 90vh;
    background-color: #f8f9fa;
    background: url('../assets/img/bg-cta.png') no-repeat center center/cover;
    display: flex;
    /* align-items: center;
    justify-content: center; */
    padding: 60px 0;
  }
  .order-card-modern {
  background: #fff;
  border-radius: 15px;
  border: 0.1px solid rgba(0, 0, 0, 0.09);
  box-shadow: 10px 6px 10px rgba(0, 0, 0, 0.12);
  padding: 20px;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
}

.order-card-modern:hover {
  transform: scale(1.02);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
}

.order-header h5 {
  margin: 0;
  font-weight: 600;
  font-size: 1.1rem;
}

.date {
  font-size: 0.85rem;
  color: #555;
}

.status {
  font-size: 0.75rem;
  padding: 5px 10px;
  border-radius: 50px;
  font-weight: 600;
  text-transform: uppercase;
}

.status.completed {
  background: #d4edda;
  color: #155724;
}

.status.processing {
  background: #d1ecf1;
  color: #0c5460;
}

/* .status.processing {
  background:rgb(187, 172, 123);
  color:rgb(93, 73, 11);
} */

.status.pending {
  background: #fff3cd;
  color: #856404;
}

.status.cancelled {
  background: #f8d7da;
  color: #721c24;
}

.order-body p {
  margin: 0.4rem 0;
}

.btn-view {
  display: inline-block;
  padding: 8px 16px;
  background:rgb(196, 175, 93);
  color:rgb(255, 251, 239);
  text-decoration: none;
  border-radius: 10px;
  font-weight: 600;
  text-align: center;
  margin-top: 10px;
  transition: 0.3s ease;
}

.btn-view:hover {
  background:rgb(203, 182, 97);
  color:rgb(247, 244, 233);
  /* color: black; */
}

</style>
<body>

<?php
$nav_active = "orders";
include 'nav.php';
?>  
<section class="checkout-section">
<div class="container">
<header class="mb-10">
    <h1 class="text-4xl font-bold text-indigo-900 mb-2">📦 My Orders</h1>
    <p class="text-gray-600">Track and manage your recent purchases</p>
</header>
<div class="container card">
  <!-- <h3 class="mb-4 mt-4 text-center">📦 my Orders</h3> -->
  <?php if ($result->num_rows > 0): ?>
    <div class="row g-4 pb-4">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="order-card-modern">
            <div class="order-header">
              <div>
                <h5>Order ID: <?= $row['order_tracker'] ?></h5>
                <p class="date">📅 <?= date('M d, Y h:i A', strtotime($row['created_at'])) ?></p>
              </div>
              <!-- <span class="status <?= $row['order_status'] == 'Completed' ? 'completed' : ($row['order_status'] == 'Cancelled' ? 'cancelled' : 'pending') ?>"> -->
              <span class="status 
            <?= $row['order_status'] == 'Completed' ? 'completed' : 
                ($row['order_status'] == 'Cancelled' ? 'cancelled' : 
                ($row['order_status'] == 'Processing' ? 'processing' : 'pending')) ?>">
                <?= $row['order_status'] ?>
              </span>
            </div>
            <div class="order-body">
              <p><strong>💳 Payment:</strong> <?= $row['payment_method'] ?></p>
              <p><strong>💰 Total:</strong> ₱<?= number_format($row['total_amount'], 2) ?></p>
            </div>
            <!-- <div class="order-footer"> -->
            <div class="order-footer d-md-flex  justify-content-md-end">
              <a href="view_order.php?order_id=<?= $row['order_id'] ?>" class="btn-view">View Items</a>
            </div>

          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      No orders found.
    </div>
  <?php endif; ?>
</div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>