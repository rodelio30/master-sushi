    <style>
        .category-btn.active {
            background-color: #D94F4F;
            color: white;
        }
        .category-btn:hover:not(.active) {
            background-color: rgba(217, 79, 79, 0.1);
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .responsive-img-container {
            height: auto; /* Default height on small screens */
            padding: 3rem 5rem 0 !important;
        }

        @media (min-width: 576px) {
            .responsive-img-container {
                height: 220px;
            }
        }

        @media (min-width: 768px) {
            .responsive-img-container {
                height: 250px;
                padding: 1rem 2rem !important;
            }
        }
    </style>
    <div id="productSectionPublic" class="container py-5 min-vh-100">
     <h2 style="margin: 2rem 0;">Available Products</h2>
        <!-- Category Filter -->
        <div class="mb-4 overflow-auto pb-2">
            <div class="d-flex flex-nowrap gap-2">
                <button class="category-btn active btn btn-light rounded-pill px-4 py-2" data-id="0">All</button>
                <?php
                $cat_query = "SELECT * FROM categories WHERE status = 'Active'";
                $cat_result = mysqli_query($conn, $cat_query);
                while ($cat = mysqli_fetch_assoc($cat_result)) {
                    $cat_id = htmlspecialchars($cat['category_id']);
                    $cat_name = htmlspecialchars($cat['category_name']);
                    echo '<button class="category-btn btn btn-light rounded-pill px-4 py-2" data-id="' . $cat_id . '">' . $cat_name . '</button>';
                }
                ?>
            </div>
        </div>

        <!-- Product Grid -->
        <!-- <div id="product-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div> -->
        <div id="product-list" class="row g-4"></div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function truncateText(text, limit) {
  return text.length > limit ? text.substring(0, limit) + '...' : text;
}

  function viewProductDetails(productId) {
    window.location.href = 'product_view.php?id=' + productId;
  }

  $(document).ready(function () {
    // Load all products on page load
    loadProducts(0); // 0 = All

    // For new button layout (Tailwind)
    $(document).on('click', '.category-btn', function () {
    //   $('.category-btn').removeClass('active bg-gray-200').addClass('bg-white');
    //   $(this).addClass('active bg-gray-200');
    $('.category-btn').removeClass('active bg-gray-200').addClass('bg-white');
  $(this).removeClass('bg-white').addClass('active bg-gray-200');

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
            const shortDesc = truncateText(product.description || 'No description', 30); // 80 characters ≈ 20 words
             html += `
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="product-card card h-100">
                        <div class="position-relative overflow-hidden p-md-4 responsive-img-container">
    <img src="../${product.first_image || 'placeholder.jpg'}" class="card-img-top w-100 h-100 object-fit-cover" alt="${product.product_name}">
    <span class="position-absolute top-0 end-0 bg-white bg-opacity-75 rounded-pill px-2 py-1 m-2 small">${product.status}</span>
</div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title">${product.product_name}</h5>
                                <p class="card-text text-muted small">${shortDesc}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="fw-medium text-dark">₱${parseFloat(product.selling_price).toFixed(2)}</span>
                                    <button onclick="viewProductDetails(${product.product_id})" class="btn btn-danger btn-sm rounded-pill">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
            });
          } else {
            html = '<p class="text-muted col-span-full">No products found for this category.</p>';
          }

          $('#product-list').html(html);
        }
      });
    }
  });
</script>