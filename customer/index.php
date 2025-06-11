<?php
include 'header.php';

$ip = getUserIP();
$timestamp = date("Y-m-d H:i:s");

$stmt_ip = $conn->prepare("INSERT INTO user_logs (ip_address, access_time) VALUES (?, ?)");
$stmt_ip->bind_param("ss", $ip, $timestamp);
$stmt_ip->execute();
$stmt_ip->close();

function getUserIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP']; // Shared internet
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return $_SERVER['HTTP_X_FORWARDED_FOR']; // Proxy
  } else {
      return $_SERVER['REMOTE_ADDR']; // Default
  }
}
?>  

<body>

<?php
$nav_active = "home";
include 'nav.php';
?>  

    
<!-- Call to Action Section -->
<section class="cta-section">
  <div class="container">
    <div class="row align-items-center">
       <!-- Left Side: Text -->
      <div class="col-md-7">
      <div class="cta-content">

        <h1 class="cta-title">FROM SUSHI ROLLS <br> TO SAUCES</h1>
        <p class="cta-subtitle">Everything You Crave, One Click Away!</p>
        <a href="#productSectionPublic" class="btn btn-gradient">EXPLORE PRODUCT</a>
      </div>
      </div>

      <!-- Right Side: Image -->
      <div class="col-md-5 text-center">
        <img src="../assets/img/right-cta.png" alt="Sushi Platter" class="img-fluid sushi-img">
      </div>
    </div>
  </div>
    <br> <br> <br> <br>
</section>


<?php include 'product_list.php';?>
 
<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>