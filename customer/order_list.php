<?php
include 'header.php';

$user_id = $_SESSION['user_id'];

// $query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
// $query->bind_param("i", $user_id);
// $query->execute();
// $result = $query->get_result();

$query = $conn->prepare("
    SELECT 
        o.order_id,
        o.order_tracker,
        o.created_at,
        o.order_status,
        o.total_amount,
        o.payment_method,
        
        oi.quantity,
        oi.price,
        
        p.product_id,
        p.product_name,
        p.selling_price,
        
        pi.image_path

    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    LEFT JOIN (
        SELECT product_id, MIN(image_path) AS image_path
        FROM product_image
        GROUP BY product_id
    ) pi ON p.product_id = pi.product_id

    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];

    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_tracker' => $row['order_tracker'],
            'created_at' => $row['created_at'],
            'order_status' => $row['order_status'],
            'total_amount' => $row['total_amount'],
            'payment_method' => $row['payment_method'],
            'items' => []
        ];
    }

    $orders[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'selling_price' => $row['selling_price'],
        'quantity' => $row['quantity'],
        'image_path' => $row['image_path']
    ];
}
?>  
<style>
  /* @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'); */
.checkout-section {
  position: relative;
  padding: 30px 13rem;
  background: none;
  overflow: hidden; /* Prevents scroll bar on overflow */
}

/* Background Layer */
.checkout-section::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('../assets/img/bg-cta.png') no-repeat center center/cover;
  opacity: 0.8; /* Adjust for desired effect */
  z-index: -1; 
}

/* Content Layer */
.checkout-section .container {
  position: relative;
  z-index: 1;
}

/* Medium screens (tablets) */
@media (max-width: 992px) {
  .checkout-section {
    padding: 30px 5rem;
  }
}

/* Small screens (mobile) */
@media (max-width: 576px) {
  .checkout-section {
    padding: 30px 1.5rem;
  }
}

.order-card {
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
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



</style>
<body>

<?php
$nav_active = "orders";
include 'nav.php';
?>  
<section class="checkout-section">
      <div class="container py-1">
        <header class="mb-4">
            <h1 class="display-5 fw-bold">📦 My Orders</h1>
            <p class="text-muted">Track and manage your recent purchases</p>
        </header>

        <div class="bg-white rounded shadow p-4 mb-4">
            <?php if (isset($_GET['cancelled']) && $_GET['cancelled'] === 'success'): ?>
                <div id="cancelAlert" class="alert alert-secondary alert-dismissible fade show" role="alert">
                    Order has been <strong>cancelled</strong> successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <script>
    setTimeout(() => {
        const alert = document.getElementById('cancelAlert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');

            // Wait for fade animation to complete before removing and reloading
            setTimeout(() => {
                alert.remove();
                window.location.href = "order_list.php"; // redirect to my_orders.php
            }, 300); // Wait 300ms for fade
        } else {
            // If alert doesn't exist, just reload
            location.reload();
        }
    }, 3000); // Initial wait 3 seconds
</script>

            <?php endif; ?>
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h5 fw-semibold text-dark">Recent Orders</h2>
                    <!-- <p class="text-muted small">Showing 5 of 12 orders</p> -->
                </div>
                <div class="d-flex gap-2 mt-3 mt-sm-0">
                    <!-- <button class="btn btn-primary">Filter</button>
                    <button class="btn btn-outline-secondary">Sort by: Recent</button> -->
                </div>
            </div>

            <?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order_id => $order): ?>
        <div class="order-card bg-white border rounded p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <div class="mb-3 mb-sm-0">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-semibold text-dark">Order #<?= htmlspecialchars($order['order_tracker']) ?></span>
                        <span class="status-badge ms-3 
                            <?= $order['order_status'] === 'Completed' ? 'status-delivered' :
                               ($order['order_status'] === 'Processing' ? 'status-processing' :
                               ($order['order_status'] === 'Cancelled' ? 'status-cancelled' : 'status-pending')) ?>">
                            <?= htmlspecialchars($order['order_status']) ?>
                        </span>
                      
                    </div>
                    <p class="text-muted small">Placed on <?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></p>
                </div>
                <div class="d-flex gap-2">
                    <!-- <a href="track_order.php?order_id=<?= $order_id ?>" class="btn btn-outline-primary btn-sm">Track</a> -->
                    <?php if ($order['order_status'] === 'Pending'): ?>
                        <a href="cancel_order.php?order_id=<?= $order_id ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel Order</a>
                    <?php endif; ?>
                    <a href="view_order.php?order_id=<?= $order_id ?>" class="btn btn-outline-primary btn-sm">Details</a>
                </div>
            </div>

            <!-- Order items -->
            <?php foreach ($order['items'] as $item): ?>
                <div class="mt-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-light rounded me-3" style="width: 64px; height: 64px;">
                        <?php if (!empty($item['image_path'])): ?>
                            <img src="../<?= htmlspecialchars($item['image_path']) ?>" alt="Product Image" class="img-fluid rounded" style="max-height: 60px;">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="text-secondary" viewBox="0 0 24 24">
                                <path d="M16 11V7a4 4 0 0 0-8 0v4H5l1 12h12l1-12h-3z" />
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="fw-medium text-dark mb-0"><?= htmlspecialchars($item['product_name']) ?></p>
                        <p class="text-muted small mb-0">₱<?= number_format($item['selling_price'], 2) ?> • Qty: <?= $item['quantity'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info text-center">
        No orders found.
    </div>
<?php endif; ?>

            <!-- <div class="mt-4 text-center">
                <button class="btn btn-outline-primary d-inline-flex align-items-center">
                    View All Orders
                    <svg xmlns="http://www.w3.org/2000/svg" class="ms-2" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div> -->
        </div>

        <div class="bg-white rounded shadow p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h3 class="h6 fw-semibold text-primary">Need help with an order?</h3>
                    <p class="text-primary">Our customer service team is here to help</p>
                </div>
                <!-- <button>Contact Support</button> -->
                <a href="contact.php" class="btn btn-primary">Contact Support</a>
            </div>
        </div>
    </div>

</section>


<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</html>
