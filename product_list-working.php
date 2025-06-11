    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Noto+Serif+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
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
    </style>
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Category Filter -->
        <div class="mb-10 overflow-x-auto pb-2">
            <div class="flex space-x-2 md:space-x-4 min-w-max">
                <!-- All Products Button -->
                <button class="category-btn active px-6 py-2 rounded-full shadow-md text-sm font-medium" data-id="0">All</button>

                <?php
                $cat_query = "SELECT * FROM categories WHERE status = 'Active'";
                $cat_result = mysqli_query($conn, $cat_query);
                while ($cat = mysqli_fetch_assoc($cat_result)) {
                    $cat_id = htmlspecialchars($cat['category_id']);
                    $cat_name = htmlspecialchars($cat['category_name']);
                    echo '<button class="category-btn px-6 py-2 bg-white rounded-full shadow-md text-sm font-medium" data-id="' . $cat_id . '">' . $cat_name . '</button>';
                }
                ?>
            </div>
        </div>

        <!-- Product Grid -->
        <div id="product-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
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
        url: 'fetch_products.php',
        type: 'POST',
        data: { category_id: categoryId },
        dataType: 'json',
        success: function (products) {
          let html = '';
          if (products.length > 0) {
            products.forEach(product => {
            const shortDesc = truncateText(product.description || 'No description', 30); // 80 characters ≈ 20 words
              html += `
              <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                  <div class="h-48 bg-gray-200 relative">
                      <img src="${product.first_image || 'placeholder.jpg'}" alt="${product.product_name}" class="w-full h-full object-cover">
                      <div class="absolute top-2 right-2 bg-white bg-opacity-80 rounded-full px-2 py-1 text-xs font-medium text-gray-700">${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}</div>
                  </div>
                  <div class="p-4">
                      <h3 class="font-bold text-lg mb-1">${product.product_name}</h3>
                        <p class="text-gray-600 text-sm mb-3">${shortDesc}</p>
                      <div class="flex justify-between items-center">
                          <span class="font-medium text-lg">₱${parseFloat(product.selling_price).toFixed(2)}</span>
                          <button onclick="viewProductDetails(${product.product_id})" class="add-btn bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">View</button>
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