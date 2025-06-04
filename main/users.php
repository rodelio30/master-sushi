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
                        <h1 class="mt-4">User Management</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                            <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Add New User
                            </button>
                        </div>
                       
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                User List 
                            </div>
                            <div class="card-body">
                            <table id="datatablesSimple2" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody> <!-- Leave empty; will be filled with JS -->
                            </table>
                            </div>
                        </div>
                    </div>
                </main>
 <!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editUserForm">

      <div class="modal-body">
        <!-- <input type="hidden" id="editUserId"> -->
        <input type="hidden" id="editUserId" name="user_id">
        <div class="mb-2">
            <div class="row">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" id="editFirstname" name="first_name" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" id="editLastname" name="last_name" class="form-control">
                </div>
            </div>
        </div>
        <div class="mb-2">
          <label>Username</label>
          <input type="text" id="editUsername" name="username" class="form-control">
        </div>
        <div class="mb-2">
          <label>Email</label>
          <input type="email" id="editEmail" name="email" class="form-control">
        </div>
        <div class="mb-2">
          <label>Role</label>
          <select id="editRole" name="role" class="form-control">
            <option>Staff</option>
            <option>Customer</option>
          </select>
        </div>
        <div class="mb-2">
            <label>Contact Number</label>
            <input type="text" id="editContactNumber" name="contact_number" class="form-control">
        </div>
        <div class="mb-2">
          <label>Address</label>
          <input type="text" id="editAddress" name="address" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <!-- <button type="submit" class="btn btn-primary">Save changes</button> -->
        <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
      </div>
      </form>

    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteUserForm">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Deactivate</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="deleteUserId" name="user_id">
          <p>Are you sure you want to deactivate <strong id="deleteUserName"></strong>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Archive</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Create User -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">User Creation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="registerForm">
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-1">
              <label>First Name</label>
              <input type="text" name="first_name" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-1">
              <label>Last Name</label>
              <input type="text" name="last_name" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-1">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-1">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-1">
              <label>Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-1">
              <label>Contact Number</label>
              <input type="text" name="contact_number" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="mb-1">
          <label>Address</label>
          <input type="text" name="address" class="form-control" required>
        </div>
        <div class="mb-1">
          <label>Role</label>
          <select name="role" class="form-control" required>
            <option>Staff</option>
            <option>Customer</option>
          </select>
        </div>
        <div class="mb-1">
          <label>Status</label>
          <select name="status" class="form-control" required>
            <option>Active</option>
            <option>Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Account</button>
      </div>
</form>
    </div>
  </div>
</div>

<!-- Modal Create User -->



<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $.ajax({
        url: 'worker/fetch_users.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let tbody = $('#datatablesSimple2 tbody');
            tbody.empty(); // Clear existing rows if any

            response.data.forEach(user => {
                tbody.append(`
                    <tr>
                        <td>${user.first_name} ${user.last_name}</td>
                        <td>${user.address || 'N/A'}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>${user.status}</td>
                        <td>
                              <button class="btn btn-sm btn-primary editBtn"
        data-bs-toggle="modal"
        data-bs-target="#editModal"
        data-user_id="${user.user_id}"
        data-first_name="${user.first_name}"
        data-last_name="${user.last_name}"
        data-username="${user.username}"
        data-email="${user.email}"
        data-role="${user.role}"
        data-address="${user.address}"
        data-contact_number="${user.contact_number}"
      >Edit</button>

      <button class="btn btn-sm btn-danger deleteBtn"
  data-id="${user.user_id}"
  data-fullname="${user.first_name} ${user.last_name}"
  data-bs-toggle="modal"
  data-bs-target="#deleteModal"
>
Archive
</button>
                        </td>
                    </tr>
                `);
            });
            // 👇 Initialize Simple-DataTable AFTER rows are added
            const datatablesSimple2 = document.getElementById('datatablesSimple2');
            if (datatablesSimple2) {
                new simpleDatatables.DataTable(datatablesSimple2);
            }
        },
        error: function() {
            alert('Failed to load user data.');
        }
    });
});

$(document).on('click', '.editBtn', function () {
    const button = $(this);
    const modal = $('#editModal');

    modal.find('.modal-title').text('Edit User');
    modal.find('#editUserId').val(button.data('user_id'));
    modal.find('#editFirstname').val(button.data('first_name'));
    modal.find('#editLastname').val(button.data('last_name'));
    modal.find('#editUsername').val(button.data('username'));
    modal.find('#editAddress').val(button.data('address'));
    modal.find('#editContactNumber').val(button.data('contact_number'));
    modal.find('#editEmail').val(button.data('email'));
    modal.find('#editRole').val(button.data('role'));
});

// $('#editUserForm').on('submit', function (e) {
  $('#saveChangesBtn').on('click', function (e) {
    e.preventDefault();

    let form = $('#editUserForm');
    $.ajax({
        url: 'worker/update_users.php',
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#editModal').modal('hide');
                alert('User updated successfully!');
                location.reload();
            } else {
                alert(response.message || 'Something went wrong');
            }
        },
        error: function () {
            alert('Failed to update user. Please try again.');
        }
    });
});

// EDIT
$(document).on('click', '.deleteBtn', function () {
  const userId = $(this).data('id');
  const fullName = $(this).data('fullname');
  $('#deleteUserId').val(userId);
  $('#deleteUserName').text(fullName);
});
$('#deleteUserForm').on('submit', function (e) {
  e.preventDefault();

  $.ajax({
    url: 'worker/delete_user.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function (response) {
      if (response.status === 'success') {
        $('#deleteModal').modal('hide');
        alert('User Archive successfully!');
          location.reload();
      } else {
        alert(response.message || 'Failed to archive user.');
      }
    },
    error: function () {
      alert('Error deleting user.');
    }
  });
});



// User Creation
$('#registerForm').on('submit', function (e) {
  e.preventDefault();

  $.ajax({
    url: 'worker/create_user.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function (response) {
      if (response.status === 'success') {
        $('#exampleModal').modal('hide');
        alert('User created successfully!');
        $('#registerForm')[0].reset(); 
        location.reload();
      } else {
        alert(response.message || 'Failed to create user.');
      }
    },
    error: function () {
      alert('Error creating user.');
    }
  });
});
</script>