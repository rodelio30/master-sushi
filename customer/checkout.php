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
// $cartQuery = $conn->prepare("
//   SELECT c.product_id, c.quantity, p.product_name, p.selling_price 
//   FROM cart c 
//   JOIN products p ON c.product_id = p.product_id 
//   WHERE c.user_id = ?
// ");
$cartQuery = $conn->prepare("
  SELECT 
    c.product_id, 
    c.quantity, 
    p.product_name, 
    p.selling_price,
    (
      SELECT pi.image_path 
      FROM product_image pi 
      WHERE pi.product_id = p.product_id 
      ORDER BY pi.created_at ASC 
      LIMIT 1
    ) AS image_path
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


// Check shop status
$status_query = $conn->query("SELECT status FROM shop_status LIMIT 1");
$shop_status = $status_query->fetch_assoc()['status'] ?? 'offline';

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

  .qtyCheck-btn {
  padding: 5px 3px;
  font-size: 12px;
  border: 1px solid #ccc;
  background-color:rgb(231, 233, 236);
  color: #333;
  border-radius: 4px;
  width: 20px;
  height: 25px;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.qtyCheck-input {
  width: 25px;
  height: 25px;
  font-size: 14px;
  padding: 0 2px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.product-img {
  width: 70px;
}

@media (max-width: 576px) {
  .product-img {
    width: 50px; /* smaller on mobile */
  }
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

              <div class="cart-item">
                <div class="d-flex justify-content-between align-items-center">
                  <!-- Left side: Product name and price -->
                  <div class="d-flex align-items-center">
                    <img src="../<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="product-img me-3 rounded"/>
                    <div>
                      <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                      <span class="text-success">₱<?= number_format($item['selling_price'], 2) ?></span>
                    </div>
                  </div>

                  <!-- Right side: Quantity controls -->
                  <div class="quantity-wrapper mt-2 d-flex align-items-center">
                    <button type="button" class="qtyCheck-btn decrease-btn" data-id="<?= $item['product_id'] ?>">-</button>
                    <input type="text" class="qtyCheck-input text-center mx-2" id="qtyCheck-<?= $item['product_id'] ?>" value="<?= $item['quantity'] ?>" readonly>
                    <button type="button" class="qtyCheck-btn increase-btn" data-id="<?= $item['product_id'] ?>">+</button>
                  </div>
                </div>
                  <!-- 👇 This will float to the right below -->
                  <div class="d-flex justify-content-end">
                    <small class="text-muted">total 
                    <span id="priceCheckout-<?= $item['product_id'] ?>" class="text-success" data-unit-price="<?= $item['selling_price'] ?>">
                      ₱<?= number_format($item['line_total'], 2) ?>
                    </span>
                  </small>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted">Your cart is empty.</p>
            <?php endif; ?>
          </div>

          <!-- Total -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <span class="cart-total">Total:</span>
            <span id="cartTotalCheckout" class="cart-total">₱<?= number_format($total, 2) ?></span>
          </div>

          <!-- Checkout Form -->
          <form action="place_order.php" method="POST">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" name="total" value="<?= $total ?>">

            <div class="mb-3">
              <label for="fullname" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullname" name="fullname"
                     value="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" name="address" rows="2" readonly><?= htmlspecialchars($user['address']) ?></textarea>
            </div>
            <div class="mb-3">
              <label for="fullname" class="form-label">Contact Number</label>
              <input type="text" class="form-control" id="contact_number" name="contact_number"
                     value="<?= htmlspecialchars($user['contact_number']) ?>" readonly>
            </div>
            <!-- <div class="mb-3">
              <label for="payment" class="form-label">Payment Method <span class="text-danger">*</span></label>
              <select class="form-select" id="payment" name="payment_method" required>
                <option value="" selected disabled>-- Select --</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="gcash" disabled>Gcash</option>
              </select>
            </div> -->

            <!-- Bank -->
             <div class="mb-3">
  <label for="payment" class="form-label">Payment Method <span class="text-danger">*</span></label>
  <select class="form-select" id="payment" name="payment_method" required>
    <option value="" selected disabled>-- Select --</option>
    <option value="Bank Transfer">Bank Transfer</option>
    <option value="Gcash" >Gcash</option>
  </select>
</div>

<!-- Bank Details -->
<div id="bankDetails" class="alert alert-light border d-none">
  <strong>Bank Transfer Details:</strong><br>
  <div class="mt-2 mb-1">
    Bank Name: <strong>Security Bank</strong><br>
  </div>
  Account Name: 
  <span id="bankName">master sushi food kiosk</span>
  <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('bankName')">Copy</button><br>
  Account Number: 
  <span id="bankAccount">0000030504940</span>
  <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('bankAccount')">Copy</button>
</div>

<!-- GCash Details -->
<div id="gcashDetails" class="alert alert-light border d-none">
  <div class="row">
    <div class="col-md-7">
      <strong>GCash Details:</strong><br>
      Account Name: 
      <span id="gcashName">Jennifer N</span>
      <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('gcashName')">Copy</button><br>
      Number: 
      <span id="gcashNumber">09613165793</span>
      <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('gcashNumber')">Copy</button>
    </div>
    <div class="col-md-5 text-center">
      <!-- <img src="../assets/img/qr-code-ms.jpeg" alt="GCash QR Code" class="img-fluid border rounded" style="max-height: 150px;">
      <div class="small mt-2">Scan QR to Pay</div> -->
        <div>
          <img src="../assets/img/qr-code-ms.jpeg" alt="GCash QR Code" class="img-fluid border rounded" style="max-height: 200px;">
        </div>
        <!-- Download button overlay -->
        <a href="../assets/img/qr-code-ms.jpeg" download="gcash_qr.png"
          class="btn btn-sm btn-success mt-2"
          title="Download QR">
          Download QR
        </a>
      <div class="text-center small mt-2">Scan QR to Pay</div>
    </div>
  </div>
</div>
            <!-- Bank -->
            <!-- Pickup Date (excluding Sundays) -->
<div class="mb-3">
  <div class="row">
    <div class="col-md-6 mb-3 mb-md-0">

  <label for="pickup_date" class="form-label">Pickup Date</label>
  <input type="date" name="pickup_date" id="pickup_date" class="form-control" required>
            </div>

    <div class="col-md-6">
<!-- Pickup Time -->
 <label for="pickup_time" class="form-label">Pickup Time</label>
  <select name="pickup_time" id="pickup_time" class="form-select" required>
    <option value="">-- Select a time slot --</option>
    <option value="10:00 AM - 11:00 AM" data-hour="10">10:00 AM - 11:00 AM</option>
    <option value="11:00 AM - 12:00 PM" data-hour="11">11:00 AM - 12:00 PM</option>
    <option value="12:00 PM - 1:00 PM" data-hour="12">12:00 PM - 1:00 PM</option>
    <option value="1:00 PM - 2:00 PM" data-hour="13">1:00 PM - 2:00 PM</option>
    <option value="2:00 PM - 3:00 PM" data-hour="14">2:00 PM - 3:00 PM</option>
    <option value="3:00 PM - 4:00 PM" data-hour="15">3:00 PM - 4:00 PM</option>
    <option value="4:00 PM - 5:00 PM" data-hour="16">4:00 PM - 5:00 PM</option>
    <option value="5:00 PM - 6:00 PM" data-hour="17">5:00 PM - 6:00 PM</option>
    <option value="6:00 PM - 7:00 PM" data-hour="18">6:00 PM - 7:00 PM</option>
  </select>
    </div>
  </div>
</div>
            <div class="mb-3">
              <label for="transaction_type" class="form-label">Transaction Type</label>
              <input type="text" class="form-control" id="transaction_type" name="transaction_type" value="Pick Up" placeholder="Pick Up" readonly>
              <!-- <select class="form-select" id="transaction_type" name="transaction_type" required>
                <option value="" disabled selected>-- Select --</option>
                <option value="pick_up">Pickup</option>
              </select> -->
            </div>

            <?php if ($shop_status === 'offline'): ?>
              <button class="btn btn-secondary w-100" disabled>Checkout Disabled (Shop Offline)</button>
            <?php else: ?>
              <button type="submit" class="btn btn-danger w-100">Place Order</button>
            <?php endif; ?>
          </form>

        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>


<script>

  // Function to update the total quantity display

  function updateCartTotalFromDOM() {
    let total = 0;
    document.querySelectorAll('.qtyCheck-input').forEach(input => {
        const productId = input.id.split('-')[1];
        const qty = parseInt(input.value);
        const unitPrice = parseFloat(document.getElementById(`priceCheckout-${productId}`).dataset.unitPrice);
        total += unitPrice * qty;
    });

    document.getElementById('cartTotalCheckout').textContent = `₱ ${total.toFixed(2)}`;
}

document.querySelectorAll('.qtyCheck-btn').forEach(button => {
    button.addEventListener('click', function () {
        const input = this.parentElement.querySelector('.qtyCheck-input');
        const productId = input.id.split('-')[1];
        let quantity = parseInt(input.value);
        const priceText = document.getElementById(`priceCheckout-${productId}`);
        const unitPrice = parseFloat(priceText.dataset.unitPrice);

        if (this.textContent === '+') {
            quantity++;
        } else if (this.textContent === '-' && quantity > 1) {
            quantity--;
        }

        input.value = quantity;

        // Instantly update item's total price
        const newItemTotal = (unitPrice * quantity).toFixed(2);
        priceText.textContent = `₱ ${newItemTotal}`;

        // Recalculate overall total from DOM
        updateCartTotalFromDOM();

        // Send update to backend
        fetch('checkout_update_qty.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
                return;
            }

            // Confirm correct values from backend
            priceText.textContent = `₱ ${data.item_total}`;
            document.getElementById('cartTotalCheckout').textContent = `₱ ${data.cart_total}`;
        })
        .catch(error => {
            console.error('Update failed:', error);
            alert('An error occurred while updating the cart. Please try again.');
        });
    });
});

</script>
<!-- <script>
  const pickupDateInput = document.getElementById('pickup_date');

  pickupDateInput.addEventListener('input', function () {
    const selectedDate = new Date(this.value);
    if (selectedDate.getDay() === 0) {
      alert('Pickup is not available on Sundays. Please choose another date.');
      this.value = '';
    }
  });

  // Optional: Set minimum date to today
  const today = new Date().toISOString().split('T')[0];
  pickupDateInput.setAttribute('min', today);


    window.addEventListener('DOMContentLoaded', function () {
    const now = new Date();
    const currentHour = now.getHours();
    const minPickupHour = currentHour + 1; // 1-hour gap

    const pickupTimeSelect = document.getElementById('pickup_time');
    const options = pickupTimeSelect.querySelectorAll('option[data-hour]');

    options.forEach(option => {
      const optionHour = parseInt(option.getAttribute('data-hour'), 10);
      if (optionHour <= minPickupHour) {
        option.disabled = true;
        option.style.color = "#999"; // optional: make disabled option grey
      }
    });
  });
</script>  -->
<script>
  const pickupDateInput = document.getElementById('pickup_date');
  const pickupTimeSelect = document.getElementById('pickup_time');

  function formatDateToYYYYMMDD(date) {
    return date.toISOString().split('T')[0];
  }

  function setMinPickupDate() {
    const now = new Date();
    const currentHour = now.getHours();
    const minDate = new Date();

    if (currentHour >= 19) { // After 7 PM
      minDate.setDate(minDate.getDate() + 1); // Move to tomorrow
    }

    const formattedMinDate = formatDateToYYYYMMDD(minDate);
    pickupDateInput.setAttribute('min', formattedMinDate);
    pickupDateInput.value = formattedMinDate; // Set default to earliest available
    handleTimeOptions(minDate);
  }

  function handleTimeOptions(dateSelected) {
    const now = new Date();
    const isToday = formatDateToYYYYMMDD(now) === formatDateToYYYYMMDD(dateSelected);
    const currentHour = now.getHours();

    const options = pickupTimeSelect.querySelectorAll('option[data-hour]');
    options.forEach(option => {
      const optionHour = parseInt(option.getAttribute('data-hour'), 10);
      if (isToday && optionHour <= currentHour) {
        option.disabled = true;
        option.style.color = "#999";
      } else {
        option.disabled = false;
        option.style.color = ""; // reset
      }
    });
  }

  pickupDateInput.addEventListener('input', function () {
    const selectedDate = new Date(this.value);
    if (selectedDate.getDay() === 0) {
      alert('Pickup is not available on Sundays. Please choose another date.');
      this.value = '';
      pickupTimeSelect.innerHTML = '<option value="">-- Select a time slot --</option>';
    } else {
      handleTimeOptions(selectedDate);
    }
  });

  window.addEventListener('DOMContentLoaded', function () {
    setMinPickupDate();
  });
</script>


<script>
  const paymentSelect = document.getElementById('payment');
  const bankDetails = document.getElementById('bankDetails');
  const gcashDetails = document.getElementById('gcashDetails');

  paymentSelect.addEventListener('change', function () {
    const selected = this.value;

    // Hide both first
    bankDetails.classList.add('d-none');
    gcashDetails.classList.add('d-none');

    if (selected === 'Bank Transfer') {
      bankDetails.classList.remove('d-none');
    } else if (selected === 'Gcash') {
      gcashDetails.classList.remove('d-none');
    }
  });

  function copyToClipboard(elementId) {
    const text = document.getElementById(elementId).textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
      alert("Copied: " + text);
    }).catch(err => {
      console.error("Failed to copy: ", err);
    });
  }
</script>