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
                        <h1 class="mt-4">Order Management</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Order</li>
                            </ol>
                        </div>
                        <div class="card mb-4">
                          <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Order List
                          </div>
                          <div class="card-body">
                            <table id="datatablesSimple2" class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Email</th>
                                  <th>Phone</th>
                                  <th>Total</th>
                                  <th>Payment</th>
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
<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="order-items-content">
          <p class="text-center">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
  fetchOrders();

  function fetchOrders() {
    $.ajax({
      url: 'worker/fetch_orders.php',
      type: 'GET',
      dataType: 'json',
      success: function (orders) {
        let tbody = $('#datatablesSimple2 tbody');
        tbody.empty();

                // <td><span class="badge bg-${getStatusColor(order.order_status)}">${order.order_status}</span></td>
        if (orders.length > 0) {
          orders.forEach(function (order) {
            tbody.append(`
              <tr>
                <td>${order.customer_name}</td>
                <td>${order.customer_email}</td>
                <td>${order.customer_phone}</td>
                <td>₱${parseFloat(order.total_amount).toFixed(2)}</td>
                <td>${order.payment_method}</td>
                <td>
                  <select class="form-select form-select-sm status-dropdown" data-id="${order.order_id}" disabled>
                    <option value="Pending" ${order.order_status === 'Pending' ? 'selected' : ''}>Pending</option>
                    <option value="Processing" ${order.order_status === 'Processing' ? 'selected' : ''}>Processing</option>
                    <option value="Completed" ${order.order_status === 'Completed' ? 'selected' : ''}>Completed</option>
                    <option value="Cancelled" ${order.order_status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                  </select>
                </td>
                <td>
                <a href="order_details.php?order_id=${order.order_id}" class="btn btn-sm btn-primary view-order">
                  View
                </a>
                </td>
              </tr>
            `);
          });

                  // <button class="btn btn-sm btn-primary view-order" data-id="${order.order_id}">View</button>
          // Initialize or reinitialize the DataTable after populating data
          if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#datatablesSimple2')) {
            $('#datatablesSimple2').DataTable(); // Using jQuery DataTables
          } else if (typeof simpleDatatables !== 'undefined') {
            new simpleDatatables.DataTable(document.getElementById('datatablesSimple2')); // For simple-datatables
          }
        } else {
          tbody.append('<tr><td colspan="7" class="text-center">No orders found</td></tr>');
        }
      },
      error: function (xhr) {
        console.error("Error fetching orders:", xhr.responseText);
      }
    });
  }

  function getStatusColor(status) {
    switch (status) {
      case 'Pending': return 'warning';
      case 'Processing': return 'primary';
      case 'Completed': return 'success';
      case 'Cancelled': return 'danger';
      default: return 'secondary';
    }
  }
});
// Handle view order click
$(document).on('click', '.view-order', function () {
  const orderId = $(this).data('id');
  $('#orderDetailsModal').modal('show');

  // Load order details
  $.ajax({
    url: 'worker/fetch_order_items.php',
    type: 'GET',
    data: { order_id: orderId },
    success: function (response) {
      $('#order-items-content').html(response);
    },
    error: function () {
      $('#order-items-content').html('<p class="text-danger">Error loading order details.</p>');
    }
  });
});
$(document).on('change', '.status-dropdown', function () {
  const orderId = $(this).data('id');
  const newStatus = $(this).val();

  $.ajax({
    url: 'worker/update_order_status.php',
    type: 'POST',
    data: { order_id: orderId, status: newStatus },
    success: function (res) {
      alert(res); // Show server success message
      fetchOrders(); // refresh table
    },
    error: function (xhr) {
      // Show the exact error message from PHP
      alert(xhr.responseText || 'Failed to update order status.');
    }
  });
});
</script>
