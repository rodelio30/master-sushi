<?php
$home_active = '';
$prod_active = '';
$order_active = '';

if($nav_active === 'home') {
    $home_active = 'active';
}
if($nav_active === 'products') {
    $prod_active = 'active';
}
if($nav_active === 'orders') {
    $order_active = 'active';
}
if($nav_active === 'contact') {
    $contact_active = 'active';
}

$user_id = $_SESSION['user_id']; // After login or registration



// Get cart items for the current user
$cart_query = $conn->prepare("SELECT c.quantity, c.product_id, p.product_name, p.selling_price 
                              FROM cart c 
                              JOIN products p ON c.product_id = p.product_id 
                              WHERE c.user_id = ?");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

$cart_items = [];
$cart_total = 0;

while ($row = $cart_result->fetch_assoc()) {
    $quantity = (int)$row['quantity'];
    $price = (float)$row['selling_price'];
    $item_total = $quantity * $price;
    $cart_total += $item_total;

    $cart_items[] = [
        'product_id' => $row['product_id'],
        'name'       => $row['product_name'],
        'qty'        => $quantity,
        'price'      => $price // keep price as unit price for front-end use
    ];
}

?>
<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
  <div class="container-fluid container">
    <a class="navbar-brand" href="index.php" style="padding: 0 !important;">
        <!-- <img src="assets/img/master_sushi.jpg" alt="Master sushi Icon" class="img-fluid rounded-circle" style=""> -->
        <img src="../assets/img/master_sushi.jpg" 
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
        <!-- <li class="nav-item">
            <div class="status-badge online pulse">
            Online
            </div>

            <div class="status-badge offline pulse">
            Offline
            </div>
        </li> -->
        <li class="nav-item">
            <div id="shop-status-indicator">
                <!-- Status will be dynamically inserted here -->
            </div>
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
                <a class="nav-link <?=$order_active?>" href="order_list.php">Orders</a>
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
                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                      <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../include/signout.php">Logout</a></li>
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
        <h5 class="offcanvas-title">
            <?php if (count($cart_items) > 0): ?>
                Your Cart
            <?php endif; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group" id="cartItems">
            <?php if (count($cart_items) > 0): ?>
                <?php foreach ($cart_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <?= htmlspecialchars($item['name']) ?><br>
                            <!-- <span id="price-<?= $item['product_id'] ?>">₱ <?= number_format($item['price'] * $item['qty'], 2) ?></span> -->
                             <span id="price-<?= $item['product_id'] ?>" data-unit-price="<?= $item['price'] ?>">
    ₱ <?= number_format($item['price'] * $item['qty'], 2) ?>
</span>

                            <div class="quantity-wrapper mt-2">
                                <button type="button" class="qty-btn decrease-btn" data-id="<?= $item['product_id'] ?>">-</button>
                                <input type="text" name="quantity" value="<?= $item['qty'] ?>" id="qty-<?= $item['product_id'] ?>" class="text-center qty-input" style="width: 50px;" readonly/>
                                <button type="button" class="qty-btn increase-btn" data-id="<?= $item['product_id'] ?>">+</button>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-danger ms-2" onclick="deleteCartItem(<?= $item['product_id'] ?>)">
                            &times;
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h5>Total:</h5>
                <h5 class="me-2">
                    <span id="cartTotal">₱ <?= number_format($cart_total, 2) ?></span>
                </h5>
            </div>
            <a href="checkout.php" class="btn btn-primary w-100 mt-3">Checkout</a>
            <?php else: ?>
                <hr>
                <h4 class="text-center mt-2">Your cart is empty.</h4>
                <hr>
            <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.qty-btn').forEach(button => {
    button.addEventListener('click', function () {
        const input = this.parentElement.querySelector('.qty-input');
        const productId = input.id.split('-')[1];
        let quantity = parseInt(input.value);
        const priceText = document.getElementById(`price-${productId}`);
        const unitPrice = parseFloat(priceText.dataset.unitPrice); // We'll add this data attribute

        if (this.textContent === '+') {
            quantity++;
        } else if (this.textContent === '-' && quantity > 1) {
            quantity--;
        }

        input.value = quantity;

        // 💡 Instantly update the item's price (UX improvement)
        const newItemTotal = (unitPrice * quantity).toFixed(2);
        priceText.textContent = `₱ ${newItemTotal}`;

        // 🧠 Recalculate total from DOM
        updateCartTotalFromDOM();

        // 🔄 Send AJAX to update server
        fetch('update_cart_qty.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert("You need to login first.");
                window.location.href = "login.php";
                return;
            }

            // ✅ Optionally confirm/correct with backend totals
            priceText.textContent = `₱ ${data.item_total}`;
            document.getElementById('cartTotal').textContent = `₱ ${data.cart_total}`;
        });
    });
});

// 🔄 Recalculate cart total from all item prices in DOM
function updateCartTotalFromDOM() {
    let total = 0;
    document.querySelectorAll('.qty-input').forEach(input => {
        const productId = input.id.split('-')[1];
        const qty = parseInt(input.value);
        const unitPrice = parseFloat(document.getElementById(`price-${productId}`).dataset.unitPrice);
        total += unitPrice * qty;
    });

    document.getElementById('cartTotal').textContent = `₱ ${total.toFixed(2)}`;
}
</script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
      location.reload(); // Reload the page
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

</script>


<!-- Quantity AJAX Script -->
<!-- <script>
document.querySelectorAll('.qty-btn').forEach(button => {
    button.addEventListener('click', function () {
        const input = this.parentElement.querySelector('.qty-input');
        const productId = input.id.split('-')[1];
        let quantity = parseInt(input.value);

        if (this.textContent === '+') {
            quantity++;
        } else if (this.textContent === '-' && quantity > 1) {
            quantity--;
        }

        input.value = quantity;

        // AJAX to update cart
        fetch('update_cart_qty.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert("You need to login first.");
                window.location.href = "login.php";
                return;
            }
            document.getElementById(`price-${productId}`).textContent = `₱ ${data.item_total}`;
            document.getElementById('cartTotal').textContent = `₱ ${data.cart_total}`;
        });
    });
});
</script> -->
<script>
  function fetchShopStatus() {
    fetch('fetch_shop_status.php')
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById('shop-status-indicator');

        if (data.status === 'online') {
          container.innerHTML = `
            <div class="status-badge online pulse">
              Online
            </div>
          `;
        } else {
          container.innerHTML = `
            <div class="status-badge offline pulse">
              Offline
            </div>
          `;
        }
      })
      .catch(err => {
        console.error('Failed to fetch shop status:', err);
      });
  }

  // Call it on page load
  document.addEventListener('DOMContentLoaded', fetchShopStatus);
</script>