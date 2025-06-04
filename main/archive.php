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
                        <h1 class="mt-4">Arhive User Management</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Arhive Users</li>
                            </ol>
                        </div>
                       
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Archive User List 
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
 
<!-- Edit Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteUserForm">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="deleteUserId" name="user_id">
          <p>Are you sure you want to delete <strong id="deleteUserName"></strong> foreveer?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Activate Modal -->
<div class="modal fade" id="activeModal" tabindex="-1" aria-labelledby="activeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="activeUserForm">
        <div class="modal-header">
          <h5 class="modal-title" id="activeModalLabel">Confirm Activate</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="activeUserId" name="user_id">
          <p>Are you sure you want to active <strong id="activeUserName"></strong> again?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Confirm</button>
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
        url: 'worker/fetch_archive_users.php',
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
      <button class="btn btn-sm btn-success activeBtn"
  data-id="${user.user_id}"
  data-fullname="${user.first_name} ${user.last_name}"
  data-bs-toggle="modal"
  data-bs-target="#activeModal"
>
Activate 
</button>
      <button class="btn btn-sm btn-danger deleteBtn"
  data-id="${user.user_id}"
  data-fullname="${user.first_name} ${user.last_name}"
  data-bs-toggle="modal"
  data-bs-target="#deleteModal"
>
Delete
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


// DELETE 
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
        // loadUsers(); // refresh your table
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


// Ativate 
$(document).on('click', '.activeBtn', function () {
  const userId = $(this).data('id');
  const fullName = $(this).data('fullname');
  $('#activeUserId').val(userId);
  $('#activeUserName').text(fullName);
});
$('#activeUserForm').on('submit', function (e) {
  e.preventDefault();

  $.ajax({
    url: 'worker/active_user.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function (response) {
      if (response.status === 'success') {
        $('#activeModal').modal('hide');
        alert('User Activate successfully!');
        // loadUsers(); // refresh your table
          location.reload();
      } else {
        alert(response.message || 'Failed to activate user.');
      }
    },
    error: function () {
      alert('Error activating user.');
    }
  });
});


</script>