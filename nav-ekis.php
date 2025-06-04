<?php
$home_active = '';
$prod_active = '';
$contact_active = '';

if($nav_active === 'home') {
    $home_active = 'active';
}
if($nav_active === 'products') {
    $prod_active = 'active';
}
if($nav_active === 'contact') {
    $contact_active = 'active';
}

// Generate session token if it doesn't exist
if (!isset($_SESSION['session_token'])) {
    $_SESSION['session_token'] = bin2hex(random_bytes(32));
}

$session_token = $_SESSION['session_token'];


// Get cart items for the current session
$cart_query = $conn->prepare("SELECT gc.quantity, gc.product_id, p.product_name, p.selling_price 
                              FROM guest_cart gc 
                              JOIN products p ON gc.product_id = p.product_id 
                              WHERE gc.session_token = ?");
$cart_query->bind_param("s", $session_token);
$cart_query->execute();
$cart_result = $cart_query->get_result();

$cart_items = [];
$cart_total = 0;

while ($row = $cart_result->fetch_assoc()) {
    $item_total = $row['quantity'] * $row['selling_price'];
    $cart_total += $item_total;
    $cart_items[] = [
        'product_id' => $row['product_id'],
        'name' => $row['product_name'],
        'qty' => $row['quantity'],
        'price' => $item_total
    ];
}

?>
<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
  <div class="container-fluid container">
    <a class="navbar-brand" href="index.php" style="padding: 0 !important;">
        <!-- <img src="assets/img/master_sushi.jpg" alt="Master sushi Icon" class="img-fluid rounded-circle" style=""> -->
        <img src="assets/img/master_sushi.jpg" 
            alt="Master sushi Icon" 
            class="img-fluid rounded-circle nav-img">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link mt-2" style="padding: 0 !important;font-family: 'Luckiest Guy', cursive;letter-spacing: 3px; font-size: 30px;">
                <b>
                MASTER SUSHI
                </b>
            </a>
        </li>
      </ul>
      <div class="d-flex">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link <?=$home_active?>" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$prod_active?>" href="products.php">Products</a>
                <!-- <a class="nav-link <?=$prod_active?>" href="#productSection">Products</a> -->
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$contact_active?>" href="contact.php">Contact</a>
            </li>
            <li class="nav-item position-relative">
              <a class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#cartSidebar" style="cursor: pointer">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-badge" id="cartCount">0</span>
              </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="login.php">Login</a></li>
                </ul>
            </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Right Sidebar Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Your Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
        <ul class="list-group" id="cartItems">
  <?php if (count($cart_items) > 0): ?>
  <?php foreach ($cart_items as $item): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <div>
      <?= htmlspecialchars($item['name']) ?>
      <br>
      <span class="badge bg-success m-1 total" id="total-<?= $item['product_id'] ?>">₱ <?= number_format($item['price'], 2) ?></span>
      <p>
        <button type="button" class="qty-btn" onclick="updateQty(this, 'decrease')" data-product-id="<?= $item['product_id'] ?>" data-price="<?= $item['price'] ?>">-</button>
        <input type="text" name="quantity" value="<?= $item['qty'] ?>" id="qty-<?= $item['product_id'] ?>" class="text-center qty-input" readonly/>
        <button type="button" class="qty-btn" onclick="updateQty(this, 'increase')" data-product-id="<?= $item['product_id'] ?>" data-price="<?= $item['price'] ?>">+</button>
      </p>
    </div>
      <button class="btn btn-sm btn-danger ms-2" onclick="deleteCartItem(<?= $item['product_id'] ?>)">
        &times;
      </button>
  </li>
<?php endforeach; ?>
  <?php else: ?>
    <li class="list-group-item text-muted">Your cart is empty.</li>
  <?php endif; ?>
</ul>
<hr>
<div class="d-flex justify-content-between align-items-center">
  <h5>Total: </h5>
    <h5 class="me-2">
    <span id="cartTotal" class="badge bg-secondary">₱ <?= number_format($cart_total, 2) ?></span>
  </h5>
</div>
<a href="login.php" class="btn btn-primary w-100 mt-3">Checkout</a>

        </div>
    </div>

    <script>
function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cartCount').innerText = data.count;
        })
        .catch(error => {
            console.error('Error fetching cart count:', error);
        });
}

// Call it on page load
document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
<script>
  function deleteCartItem(productId) {
  if (!confirm("Remove this item from your cart?")) return;

  fetch('delete_cart_item.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ product_id: productId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
            localStorage.setItem('reopenCart', 'true'); // Set the flag
      location.reload(); // Reload to reflect updated cart
    } else {
      alert("Failed to delete item.");
    }
  })
  .catch(error => {
    console.error("Delete failed:", error);
  });
}
document.addEventListener('DOMContentLoaded', function () {
  updateCartCount();

  if (localStorage.getItem('reopenCart') === 'true') {
    var cartSidebar = new bootstrap.Offcanvas(document.getElementById('cartSidebar'));
    cartSidebar.show();
    localStorage.removeItem('reopenCart'); // Clear the flag
  }
});


function updateQty(button, action) {
  const productId = button.getAttribute("data-product-id");
  const qtyInput = document.getElementById("qty-" + productId);
  const totalSpan = document.getElementById("total-" + productId);
  let currentQty = parseInt(qtyInput.value);

  if (action === 'increase') {
    currentQty++;
  } else if (action === 'decrease' && currentQty > 1) {
    currentQty--;
  }

  qtyInput.value = currentQty;

  // AJAX request to update server-side
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_cart_quantity.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.success) {
        totalSpan.textContent = "₱ " + response.total_price;
      } else {
        alert("Failed to update cart.");
      }
    }
  };
  xhr.send("product_id=" + productId + "&quantity=" + currentQty);
}
</script>