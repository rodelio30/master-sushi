<?php
include 'header.php';
?>  

<style>

#productSection {
min-height: 81vh;
/* background-color: #fff; */
background: url('../assets/img/bg-cta.png') no-repeat center center/cover;
border-radius: 12px;
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
padding: 20px;
box-sizing: border-box;
}

    .category-item {
      transition: all 0.3s ease;
      position: relative;
      display: block;
      font-size: 1.125rem;
      font-weight: 600;
      color: #495057;
      text-decoration: none;
    }

    .category-item:hover {
      transform: translateX(8px);
      color: #c5b87f;
    }

    .category-item::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background-color: #c5b87f;
      transition: width 0.3s ease;
    }

    .category-item:hover::after {
      width: 100%;
    }

    .product-card {
      transition: all 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .view-details-btn {
      transition: all 0.3s ease;
    }

    .view-details-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
    }

    .active-category {
      color: #3B82F6 !important;
      font-weight: 600;
    }

    .product-image-placeholder {
      height: 12rem;
      background-color: #f8f9fa;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .product-icon {
      width: 8rem;
      height: 8rem;
      color: #adb5bd;
    }
  </style>
<body>

<?php
$nav_active = "products";
include 'nav.php';
?>  
  
<section id="productSection">
  <div class="container">

    <!-- Section Title -->
    <div class="row mt-4">
      <div class="col-md-3"> </div>
      <div class="col-md-9">
        <h2 style="margin-left: 1rem; margin-bottom: 20px; font-weight: bold;">Available Products</h2>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid mt-2">
      <div class="row">

        <!-- Category Sidebar -->
        <div class="col-md-3" style="text-align: end;">
          <h4><strong><u>Category List</u></strong></h4>
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

<!-- View Details Script -->
<script>
  function viewProductDetails(productId) {
    window.location.href = 'product_view.php?id=' + productId;
  }
</script>

<!-- Load Products Script -->
<script>
  $(document).ready(function () {
    // Load all products on initial load
    loadProducts(0);

    // Handle category click
    $('.category-item').on('click', function (e) {
      e.preventDefault();
      const categoryId = $(this).data('id');
      loadProducts(categoryId);
    });

    // Fetch and render products
    function loadProducts(categoryId) {
      $.ajax({
        url: '../fetch_products.php',
        type: 'POST',
        data: { category_id: categoryId },
        dataType: 'json',
        success: function (products) {
          let html = '';
          if (products.length > 0) {
            html += '<div class="row">';
            products.forEach(product => {
              html += `
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card h-100 shadow-sm product-card">
                    <img src="../${product.first_image || 'placeholder.jpg'}" class="card-img-top" alt="${product.product_name}" style="height: 250px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                      <h5 class="card-title">${product.product_name}</h5>
                      <p class="card-text mb-1">Quantity: ${product.quantity}</p>
                      <p class="card-text text-danger fw-bold">₱${parseFloat(product.selling_price).toFixed(2)}</p>
                      <button class="btn btn-gradient mt-auto" onclick="viewProductDetails(${product.product_id})">View Details</button>
                    </div>
                  </div>
                </div>
              `;
            });
            html += '</div>';
          } else {
            html = '<p class="text-muted">No products found for this category.</p>';
          }

          $('#product-list').html(html);
        }
      });
    }
  });
</script>