<?php
include 'header.php';

$order_tracker = $_GET['order_tracker'] ?? 0;


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
<div class="container text-center mt-5">
    <div class="alert alert-success">
      <h4 class="alert-heading">🎉 Thank you!</h4>
      <p>Your order ID: <u><?= htmlspecialchars($order_tracker) ?> </u>has been placed successfully!</p>
      <a href="products.php" class="btn btn-primary">Continue Shopping</a>
      <a href="order_list.php" class="btn btn-success">View Order</a>
    </div>
  </div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>