<?php
include 'header.php';
if (isset($_GET['id'])) {
  $productId = $_GET['id'];

  // Get product, image, and category name
  $stmt = $conn->prepare("
      SELECT 
          p.*, 
          pi.image_path, 
          c.category_name
      FROM 
          products p
      LEFT JOIN 
          product_image pi ON p.product_id = pi.product_id
      LEFT JOIN 
          categories c ON p.category_id = c.category_id
      WHERE 
          p.product_id = ?
      LIMIT 1
  ");
  $stmt->bind_param("i", $productId);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if (!$product) {
      echo '<script>alert("Error: Product not found!");  window.location.href = "index.php";</script>';
      exit;
  }
} else {
    echo '<script>alert("Error: No product ID provided!");  window.location.href = "index.php";</script>';
    exit;
}



?>  
<!-- <style>

</style> -->
<body>

<?php
$nav_active = "products";
include 'nav.php';
?>  
    
    <section id="productSection">
  <div class="container">
    <div class="product-view">
      <div class="product-image" data-bs-toggle="modal" data-bs-target="#imageModal" style="cursor: zoom-in;">
        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image" class="img-thumbnail" />
      </div>
      <!-- Modal Popup -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0">
        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Zoomed Image" class="img-fluid w-100 rounded" />
      </div>
    </div>
  </div>
</div>
      <div class="product-details">
      <?php if (isset($_GET['added']) && $_GET['added'] == 'true'): ?>
        <div class="alert alert-success" role="alert">
          Product successfully added to cart!
        </div>
      <?php endif; ?>
      <form method="POST" action="add_to_cart.php">
        <h2 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h2>
        <p class="product-description">
          <?php 
              $description = nl2br(htmlspecialchars($product['description']));
              $short_description = strlen($product['description']) > 100
                ? nl2br(htmlspecialchars(substr($product['description'], 0, 100))) . "..."
                : $description;
            ?>
       
       <span id="shortDesc"><?= $short_description ?></span>
       <span id="fullDesc" style="display: none;"><?= $description ?></span>
       <?php if (strlen($product['description']) > 100): ?>
         <a href="javascript:void(0);" onclick="toggleDescription()" id="toggleBtn" style="color: black; font-size: 14px;">See more</a>
       <?php endif; ?>
        </p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
        <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($product['status']); ?></p>

        <div class="price-info">
          <p><strong>Price:</strong> <span>P <?php echo number_format($product['selling_price'], 2); ?></span></p>
            <strong>Quantity:</strong>
          <p>
            <button  type="button" class="qty-btn" onclick="decreaseQty()">-</button>
            <input type="text" name="quantity"  value="1" id="qty" readonly />
            <button type="button" class="qty-btn" onclick="increaseQty()">+</button>
          </p>
          <p><strong>Total:</strong> <span id="total">₱ <?php echo number_format($product['selling_price'], 2); ?></span></p>
        </div>

        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
        <button type="submit" name="add_to_cart" class="add-to-cart">ADD TO CART  <i class="cart-icon">🛒</i></button>
      </form>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>
<script>
  function increaseQty() {
    let qty = document.getElementById("qty");
    let total = document.getElementById("total");
    let price = <?= $product['selling_price'] ?>;
    qty.value = parseInt(qty.value) + 1;
    total.innerText = "P " + (qty.value * price).toFixed(2);
  }

  function decreaseQty() {
    let qty = document.getElementById("qty");
    let total = document.getElementById("total");
    let price = <?= $product['selling_price'] ?>;
    if (parseInt(qty.value) > 1) {
      qty.value = parseInt(qty.value) - 1;
      total.innerText = "P " + (qty.value * price).toFixed(2);
    }
  }

  function toggleDescription() {
    const shortDesc = document.getElementById("shortDesc");
    const fullDesc = document.getElementById("fullDesc");
    const btn = document.getElementById("toggleBtn");

    if (fullDesc.style.display === "none") {
      fullDesc.style.display = "inline";
      shortDesc.style.display = "none";
      btn.textContent = "-- See less";
    } else {
      fullDesc.style.display = "none";
      shortDesc.style.display = "inline";
      btn.textContent = "See more";
    }
  }
</script>
