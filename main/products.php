<?php 
// Check Connections
include 'template/checker.php';

// Line below for the code
?>
<?php include 'template/header.php'; ?>
    <body class="sb-nav-fixed">
        <?php include 'template/topbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'template/sidebar.php'; ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Product Management</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Product</li>
                            </ol>
                            <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                Add New Product 
                            </button>
                        </div>
                        <div class="card mb-4">
                          <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Product List
                          </div>
                          <div class="card-body">
                            <table id="datatablesSimple2" class="table table-striped">
                              <thead>
                                  <tr>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Buying Price</th>
                                    <th>Selling Price</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody></tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                </main>


<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editProductModalLabel">Edit Product</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editProductForm" enctype="multipart/form-data">

        <div class="modal-body">
          <input type="hidden" id="editProductId" name="product_id">

          <div class="mb-2">
            <label>Change Product Image</label>
            <input type="file" name="product_images[]" id="product_images" class="form-control" multiple>
          </div>
          <div id="editProductImagePreview" class="d-flex flex-wrap mb-2"></div>

          <div class="mb-2">
            <label>Category</label>
            <select id="editProductCategory" name="category_id" class="form-control"></select>
          </div>

          <div class="mb-2">
            <label>Brand</label>
            <input type="text" id="editProductBrand" name="brand" class="form-control">
          </div>

          <div class="mb-2">
            <label>Product Name</label>
            <input type="text" id="editProductName" name="product_name" class="form-control">
          </div>

          <div class="mb-2">
            <label>Description</label>
            <textarea id="editProductDescription" name="description" class="form-control"></textarea>
          </div>

          <div class="mb-2">
            <label>Buying Price</label>
            <input type="number" step="0.01" id="editBuyingPrice" name="buying_price" class="form-control">
          </div>

          <div class="mb-2">
            <label>Selling Price</label>
            <input type="number" step="0.01" id="editSellingPrice" name="selling_price" class="form-control">
          </div>

          <div class="mb-2">
            <label>Quantity</label>
            <input type="number" id="editQuantity" name="quantity" class="form-control">
          </div>

          <div class="mb-2">
            <label>Status</label>
            <select id="editProductStatus" name="status" class="form-control">
              <option value="Available">Available</option>
              <option value="Out of Stock">Out of Stock</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveProductChangesBtn">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteProductForm">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="deleteProductId" name="product_id">
          <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ADD PRODUCT MODAL -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="addProductForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductLabel">Add New Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Error Message Display -->
          <div id="addProductError" class="alert alert-danger d-none"></div>

          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select name="category_id" id="productCategorySelect" class="form-control" required></select>
          </div>

          <div class="mb-3">
            <label for="product_images" class="form-label">Upload Images</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            <small>-- You can add more than 1 image --</small>
          </div>

          <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label for="buying_price" class="form-label">Buying Price</label>
            <input type="number" name="buying_price" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="selling_price" class="form-label">Selling Price</label>
            <input type="number" name="selling_price" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control" required>
              <option value="Available">Available</option>
              <option value="Out of Stock">Out of Stock</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</div>




<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
  // Fetch Products
  $.ajax({
    url: 'worker/fetch_products.php',
    type: 'GET',
    dataType: 'json',
    success: function (response) {
      let tbody = $('#datatablesSimple2 tbody');
      tbody.empty();
      response.data.forEach(product => {
  let desc = product.description;
  let shortDesc = desc.length > 100 ? desc.substring(0, 50) + '...' : desc;
  // let imageTag = product.product_image
  //   ? `<img src="../${product.product_image}" class="img-thumbnail" style="width: 60px; height: 60px;">`
  //   : `<span class="text-muted">No Image</span>`;

      // <td><img src="../${product.product_image}" alt="${product.product_name}" width="50" height="50"></td>
  tbody.append(`
    <tr>
    <td>
  ${product.images && product.images.length > 0 
    ? `<img src="../${product.images[0]}" width="40" class="me-1 mb-1 img-thumbnail">`
    : '<span class="text-muted">No Image</span>'
  }
</td>
      <td>${product.category_name}</td>
      <td>${product.brand}</td>
      <td>${product.product_name}</td>
      <td>${shortDesc}</td>
      <td>₱${product.buying_price}</td>
      <td>₱${product.selling_price}</td>
      <td>${product.quantity}</td>
      <td>${product.status}</td>
      <td>
        <button class="btn btn-sm btn-primary editProductBtn"
          data-product='${JSON.stringify(product)}' data-bs-toggle="modal" data-bs-target="#editProductModal">
          Edit
        </button>

         <?php if($global_user_role === 'Admin') { ?>
        <button class="btn btn-sm btn-danger deleteProductBtn"
          data-id="${product.product_id}" data-name="${product.product_name}" data-bs-toggle="modal" data-bs-target="#deleteProductModal">
          Delete
        </button>
         <?php } ?>
      </td>
    </tr>
  `);
});

      const datatablesSimple2 = document.getElementById('datatablesSimple2');
      if (datatablesSimple2) {
        new simpleDatatables.DataTable(datatablesSimple2);
      }
    },
    error: function () {
      alert('Failed to load categories.');
    }
  });



  $(document).ready(function () {
  // Load categories in dropdown
  function loadCategoryOptions() {
    $.ajax({
      url: 'worker/fetch_categories.php',
      method: 'GET',
      dataType: 'json',
      success: function (res) {
        let select = $('#productCategorySelect');
        select.empty();
        res.data.forEach(cat => {
          select.append(`<option value="${cat.category_id}">${cat.category_name}</option>`);
        });
      }
    });
  }

  loadCategoryOptions();

  // Submit Add Product Form
  $('#addProductForm').on('submit', function (e) {
  e.preventDefault();
  let form = $('#addProductForm')[0];
  let formData = new FormData(form);
  let errorDiv = $('#addProductError');

  $.ajax({
    url: 'worker/add_product.php',
    method: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    dataType: 'json',
    success: function (res) {
      if (res.status === 'success') {
        $('#addProductModal').modal('hide');
        $('#addProductForm')[0].reset();
        errorDiv.addClass('d-none').text('');
        location.reload();
      } else {
        errorDiv.removeClass('d-none').text(res.message);
      }
    }
  });
  });
});

$(document).on('click', '.editProductBtn', function () {
  const product = $(this).data('product');


  // Preview multiple images
  const imagePreview = $('#editProductImagePreview'); // Assuming it's a container div
  imagePreview.html(''); // Clear previous images

  if (product.images && product.images.length > 0) {
    product.images.forEach(imgPath => {
      imagePreview.append(`<img src="../${imgPath}" class="img-thumbnail m-1" width="80">`);
    });
  } else {
    imagePreview.append(`<img src="../assets/uploads/products/default-image.webp" class="img-thumbnail" width="80">`);
  }

  $('#editProductId').val(product.product_id);
  $('#editProductCategory').val(product.category_id);
  $('#editProductBrand').val(product.brand);
  $('#editProductName').val(product.product_name);
  $('#editProductDescription').val(product.description);
  $('#editBuyingPrice').val(product.buying_price);
  $('#editSellingPrice').val(product.selling_price);
  $('#editQuantity').val(product.quantity);
  $('#editProductStatus').val(product.status);
});

function loadCategoryOptionsForEdit() {
  $.ajax({
    url: 'worker/fetch_categories.php',
    method: 'GET',
    dataType: 'json',
    success: function (res) {
      let select = $('#editProductCategory');
      select.empty();
      res.data.forEach(cat => {
        select.append(`<option value="${cat.category_id}">${cat.category_name}</option>`);
      });
    }
  });
}

// Call this on page ready
loadCategoryOptionsForEdit();

$('#product_images').on('change', function () {
  let preview = $('#editProductImagePreview');
  preview.html('');
  Array.from(this.files).forEach(file => {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.append(`<img src="${e.target.result}" class="img-thumbnail m-1" width="100">`);
    };
    reader.readAsDataURL(file);
  });
});

// Submit Edit Product Form
$('#saveProductChangesBtn').on('click', function (e) {
  e.preventDefault();

  let formData = new FormData($('#editProductForm')[0]);
  $.ajax({
  url: 'worker/update_product.php',
  type: 'POST',
  data: formData,
  processData: false,
  contentType: false,
  dataType: 'json', // ✅ THIS LINE IS IMPORTANT
  success: function (res) {
    console.log("Success Response:", res);
    if (res.status === 'success') {
      $('#editProductModal').modal('hide');
      alert('Product updated successfully!');
      location.reload();
    } else {
      alert(res.message || 'Update failed.');
    }
  },
  error: function (xhr, status, error) {
    console.error("AJAX ERROR:", xhr.responseText);
    alert('Error while updating the product.');
  }
});
});

  // Trigger Delete Modal and populate info
$(document).on('click', '.deleteProductBtn', function () {
  const productId = $(this).data('id');
  const productName = $(this).data('name');

  $('#deleteProductId').val(productId);
  $('#deleteProductName').text(productName);
  $('#deleteProductModal').modal('show');
});

// Handle form submit for deletion
$('#deleteProductForm').on('submit', function (e) {
  e.preventDefault();
  const formData = $(this).serialize();

  $.ajax({
    url: 'worker/delete_product.php',
    type: 'POST',
    data: formData,
    dataType: 'json',
    success: function (res) {
      if (res.status === 'success') {
        $('#deleteProductModal').modal('hide');
        alert('Product deleted successfully!');
        location.reload();
      } else {
        alert('Delete failed: ' + res.message);
      }
    },
    error: function () {
      alert('Something went wrong while deleting the product.');
    }
  });
});
});

</script>