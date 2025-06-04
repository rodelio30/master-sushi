<?php
include 'header.php';
?>  
<style>

/* #productSection {
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
} */
#productSection {
min-height: 100vh;
/* background-color: #fff; */
background: url('assets/img/bg-cta.png') no-repeat center center/cover;
border-radius: 12px;
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
padding: 20px;
box-sizing: border-box;
}


.product-container {
display: flex;
flex-wrap: wrap;
gap: 20px;
justify-content: start;
}
.product-card {
width: 300px;
height: auto;
background: #ffffff;
padding: 14px;
border-radius: 18px;
display: flex;
flex-direction: column;
justify-content: space-between;
gap: 14px;
box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-card:hover {
transform: translateY(-6px);
box-shadow: 0 10px 18px rgba(0, 0, 0, 0.12);
}

.product-img {
  width: 100%;
  height: 292px;
  background-color: #f3f4f6;
  border-radius: 10px;
  border: 1px solid rgb(206, 206, 206);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background-size: cover;
  background-position: center;
}

.product-img img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}
.product-img:hover img {
transform: scale(1.05);
}

.product-details {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-top: 4px;
}

.product-info {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.product-text {
  display: flex;
  flex-direction: column;
}

.product-name {
  font-size: 1.1rem;
  font-weight: bold;
  line-height: 1.2;
  min-height: 2.5em;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.product-qty {
  font-size: 0.75rem;
  color: #374151;
  margin-top: 2px;
}

.product-price {
  font-weight: bold;
  color: #dc2626;
}

.view-btn {
margin-top: auto;
background: linear-gradient(135deg, #2563eb, #1d4ed8);
color: #fff;
border: none;
padding: 10px;
border-radius: 8px;
font-size: 0.95rem;
cursor: pointer;
transition: background 0.3s ease;
}

.view-btn:hover {
background: linear-gradient(135deg, #1e40af, #1e3a8a);
}

.category-item {
  font-size: 1.2rem;
  padding: 5px 10px;
  display: block;
  text-decoration: none;
  text-align: end;
  color: black;
}

.active-cat {
  font-weight: bold;
  color: #0d6efd;
  border-left: 4px solid #0d6efd;
  padding-left: 6px;
}

</style>
<body>

<?php
$nav_active = "products";
include 'nav.php';
?>  
    
<!-- Call to Action Section -->
<section id="productSection">
<div class="container">

<div class="row mt-4">
<div class="col-md-3"> </div>
<div class="col-md-9"> 
  <h2 style="margin-left: 1rem;margin-bottom: 20px; font-weight: bold;">Available Products</h2>
</div>
</div>
<div class="container-fluid mt-2">
<div class="row">
<!-- Category Sidebar -->
<div class="col-md-3">
  <h4 style="text-align: end;"><strong><u>Category List</u></strong></h4>
  <ul class="list-unstyled">
    <li><a href="#productSection" class="category-item" data-id="0">All Products</a></li>
    <?php
      $cat_query = "SELECT * FROM categories WHERE status = 'Active'";
      $cat_result = mysqli_query($conn, $cat_query);
      while ($cat = mysqli_fetch_assoc($cat_result)) {
        echo '<li><a href="#productSection" class="category-item" data-id="' . $cat['category_id'] . '">' . htmlspecialchars($cat['category_name']) . '</a></li>';
      }
    ?>
  </ul>
</div>

<!-- Product Grid -->
<div class="col-md-9">
  <div id="product-list" class="product-container"></div>

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
  function viewProductDetails(productId) {
  // You can open a modal or redirect to a product detail page
  console.log("View details for product ID:", productId);
  window.location.href = 'product_view.php?id=' + productId;
}
</script>

<script>
   $(document).ready(function () {
    // Load all products on page load
    loadProducts(0); // 0 = all

    $('.category-item').on('click', function () {
      const categoryId = $(this).data('id');
      loadProducts(categoryId);
    });

    function loadProducts(categoryId) {
      $.ajax({
        url: '../fetch_products.php',
        type: 'POST',
        data: { category_id: categoryId },
        dataType: 'json',
        success: function (products) {
          let html = '';
          if (products.length > 0) {
            products.forEach(product => {
              html += `
                <div class="product-card">
                  <div class="product-img" style="background-image: url('../${product.first_image || 'placeholder.jpg'}');"></div>
                  <div class="product-details">
                    <div class="product-info">
                      <div class="product-text">
                        <span class="product-name">${product.product_name.split(' ').slice(0,2).join(' ')}</span>
                        <p class="product-qty">Qty: ${product.quantity}</p>
                      </div>
                      <span class="product-price">₱${parseFloat(product.selling_price).toFixed(2)}</span>
                    </div>
                    <button class="btn btn-gradient" onclick="viewProductDetails(${product.product_id})">View Details</button>
                  </div>
                </div>
              `;
            });
          } else {
            html = '<p class="text-muted">No products found for this category.</p>';
          }

          $('#product-list').html(html);
        }
      });
    }
  });
</script>