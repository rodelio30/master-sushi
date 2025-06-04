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
                        <h1 class="mt-4">Category Management</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Category</li>
                            </ol>
                            <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Add New Category 
                            </button>
                        </div>
                        <div class="card mb-4">
                          <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Category List
                          </div>
                          <div class="card-body">
                            <table id="datatablesSimple2" class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Description</th>
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


<!-- Edit Category Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editCategoryForm">
        <div class="modal-body">
          <input type="hidden" id="editCategoryId" name="category_id">
          <div class="mb-2">
            <label>Category Name</label>
            <input type="text" id="editCategoryName" name="category_name" class="form-control">
          </div>
          <div class="mb-2">
            <label>Description</label>
            <textarea id="editCategoryDescription" name="description" class="form-control"></textarea>
          </div>
          <div class="mb-2">
            <label>Status</label>
            <select id="editCategoryStatus" name="status" class="form-control">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveCategoryChangesBtn">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteCategoryForm">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="deleteCategoryId" name="category_id">
          <p>Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addCategoryForm">
        <div class="modal-body">
          <div class="mb-2">
            <label>Category Name</label>
            <input type="text" name="category_name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
          </div>
          <div class="mb-2">
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</div>




<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
  // Fetch Categories
  $.ajax({
    url: 'worker/fetch_categories.php',
    type: 'GET',
    dataType: 'json',
    success: function (response) {
      let tbody = $('#datatablesSimple2 tbody');
      tbody.empty();
      response.data.forEach(category => {
        tbody.append(`
          <tr>
            <td>${category.category_name}</td>
            <td>${category.description}</td>
            <td>${category.status}</td>
            <td>
              <button class="btn btn-sm btn-primary editBtn"
                data-bs-toggle="modal"
                data-bs-target="#editModal"
                data-category_id="${category.category_id}"
                data-category_name="${category.category_name}"
                data-description="${category.description}"
                data-status="${category.status}">
                Edit
              </button>
         <?php if($global_user_role === 'Admin') { ?>
              <button class="btn btn-sm btn-danger deleteBtn"
                data-id="${category.category_id}"
                data-name="${category.category_name}"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal">
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

  // Fill Edit Modal
  $(document).on('click', '.editBtn', function () {
    const btn = $(this);
    $('#editCategoryId').val(btn.data('category_id'));
    $('#editCategoryName').val(btn.data('category_name'));
    $('#editCategoryDescription').val(btn.data('description'));
    $('#editCategoryStatus').val(btn.data('status'));
  });

  // Submit Edit Form
  $('#saveCategoryChangesBtn').on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: 'worker/update_category.php',
      type: 'POST',
      data: $('#editCategoryForm').serialize(),
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          $('#editModal').modal('hide');
          alert('Category updated successfully!');
          location.reload();
        } else {
          alert(res.message || 'Update failed.');
        }
      },
      error: function () {
        alert('Error while updating category.');
      }
    });
  });

  // Fill Delete Modal
  $(document).on('click', '.deleteBtn', function () {
    $('#deleteCategoryId').val($(this).data('id'));
    $('#deleteCategoryName').text($(this).data('name'));
  });

  // Submit Delete
  $('#deleteCategoryForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: 'worker/delete_category.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          $('#deleteModal').modal('hide');
          alert('Category deleted successfully!');
          location.reload();
        } else {
          alert(res.message || 'Delete failed.');
        }
      },
      error: function () {
        alert('Error while deleting category.');
      }
    });
  });
});



$('#addCategoryForm').on('submit', function (e) {
  e.preventDefault();

  $.ajax({
    url: 'worker/add_category.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function (response) {
      if (response.status === 'success') {
        $('#exampleModal').modal('hide');
        alert('Category added!');
        $('#addCategoryForm')[0].reset(); 
        location.reload();
      } else {
        // Show specific validation message
        alert(response.message || 'Failed to create category.');
      }
    },
    error: function () {
      alert('Error communicating with server.');
    }
  });
});
</script>