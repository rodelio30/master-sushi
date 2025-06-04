<?php 
// Check Connections
include 'template/checker.php';

// Line below for the code
$sql = "SELECT 
            MONTH(created_at) AS month,
            COUNT(*) AS total_orders
        FROM orders
        GROUP BY MONTH(created_at)
        ORDER BY month";

$result = mysqli_query($conn, $sql);

$monthly_labels = [];
$monthly_data = [];

$months = [
    1 => "January", 2 => "February", 3 => "March", 4 => "April",
    5 => "May", 6 => "June", 7 => "July", 8 => "August",
    9 => "September", 10 => "October", 11 => "November", 12 => "December"
];

while ($row = mysqli_fetch_assoc($result)) {
    $monthly_labels[] = $months[(int)$row['month']];
    $monthly_data[] = (int)$row['total_orders'];
}
?>
<?php include 'template/header.php'; ?>
<style>
.dashboard-card {
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  transition: 0.3s;
}

.dashboard-card:hover {
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.dashboard-card-title {
  font-weight: 600;
  font-size: 14px;
  color: #6b7280;
}

.dashboard-card-value {
  font-size: 28px;
  font-weight: bold;
  color: #111827;
}

.dashboard-footer {
  font-size: 13px;
  color: #6b7280;
}
.label {
  height: 60px;
  width: 120px;
  background-color:rgb(160, 172, 177);
  border-radius: 30px;
  -webkit-box-shadow: inset 0 0 5px 4px rgba(255, 255, 255, 1),
    inset 0 0 20px 1px rgba(0, 0, 0, 0.488), 10px 20px 30px rgba(0, 0, 0, 0.096),
    inset 0 0 0 3px rgba(0, 0, 0, 0.3);
  box-shadow: inset 0 0 5px 4px rgba(255, 255, 255, 1),
    inset 0 0 20px 1px rgba(0, 0, 0, 0.488), 10px 20px 30px rgba(0, 0, 0, 0.096),
    inset 0 0 0 3px rgba(0, 0, 0, 0.3);
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  cursor: pointer;
  position: relative;
  -webkit-transition: -webkit-transform 0.4s;
  transition: -webkit-transform 0.4s;
  transition: transform 0.4s;
}

.label:hover {
  -webkit-transform: perspective(100px) rotateX(5deg) rotateY(-5deg);
  transform: perspective(100px) rotateX(5deg) rotateY(-5deg);
}

#checkbox:checked ~ .label:hover {
  -webkit-transform: perspective(100px) rotateX(-5deg) rotateY(5deg);
  transform: perspective(100px) rotateX(-5deg) rotateY(5deg);
}

#checkbox {
  display: none;
}

#checkbox:checked ~ .label::before {
  left: 70px;
  background-color: #000000;
  background-image: linear-gradient(315deg, #000000 0%, #414141 70%);
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.label::before {
  position: absolute;
  content: "";
  height: 40px;
  width: 40px;
  border-radius: 50%;
  background-color: #000000;
  background-image: linear-gradient(
    130deg,
    #757272 10%,
    #ffffff 11%,
    #726f6f 62%
  );
  left: 10px;
  -webkit-box-shadow: 0 2px 1px rgba(0, 0, 0, 0.3),
    10px 10px 10px rgba(0, 0, 0, 0.3);
  box-shadow: 0 2px 1px rgba(0, 0, 0, 0.3), 10px 10px 10px rgba(0, 0, 0, 0.3);
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

</style>
    <body class="sb-nav-fixed">
        <?php include 'template/topbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'template/sidebar.php'; ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                      <div class="d-flex justify-content-between align-items-center">
  <h1 class="mt-4">Dashboard</h1>

  <div class="mt-4 d-flex align-items-center gap-2">
    <span id="shop-status-text" class="fw-bold text-muted">Shop is Closed</span>

    <div>
      <input type="checkbox" name="checkbox" id="checkbox" />
      <label for="checkbox" class="label"></label>
    </div>
  </div>
</div>
                      <!-- <div class="d-flex justify-content-between">
                        <h1 class="mt-4">Dashboard</h1>
                        <div class="mt-4">
                          <input type="checkbox" name="checkbox" id="checkbox" />
                          <label for="checkbox" class="label"> </label>
                        </div>
                      </div> -->

                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <?php if($global_user_role === 'Admin') { ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area me-1"></i>
                                Analytics
                                <div class="float-end">
                                  <a href="analytics.php" class="text-dark">View Site Analytics</a>
                                </div>
                            </div>
                            <div class="card-body">
                        <div class="row g-3">
  <div class="col-md-6 col-xl-3">
    <div class="dashboard-card">
      <div class="dashboard-card-title">Site Sessions</div>
      <div class="d-flex align-items-center justify-content-between mt-2">
        <div id="session-value" class="dashboard-card-value">Loading...</div>
        <div id="session-change" class="text-danger fw-semibold">Loading...</div>
      </div>
      <div id="session-footer" class="dashboard-footer mt-2">
        Loading...
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <div class="dashboard-card">
      <div class="dashboard-card-title">Total Sales</div>
      <div id="total-sales-value" class="dashboard-card-value mt-2">₱0.00</div>
      <div id="total-sales-footer" class="dashboard-footer mt-2">No sales yet</div>
    </div>
  </div>


  <div class="col-md-6 col-xl-3">
    <div class="dashboard-card">
      <div class="dashboard-card-title">Total Orders</div>
      <div id="total-orders-value" class="dashboard-card-value mt-2">0</div>
      <div id="total-orders-footer" class="dashboard-footer mt-2">No orders yet</div>
    </div>
  </div>


  <div class="col-md-6 col-xl-3">
    <div class="dashboard-card">
      <div class="dashboard-card-title">New Users</div>
      <div id="new-users-value" class="dashboard-card-value mt-2">0</div>
      <div id="new-users-footer" class="dashboard-footer mt-2">No new subscribers</div>
    </div>
  </div>

</div>
                            </div>
                          </div>
                        <div class="row mt-4">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Sales Performance
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Monthly Orders Overview
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-user me-1"></i>
                                Modal Sample
                            </div>
                            <div class="card-body">
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
                            </div>
                        </div> -->
                        <?php } ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Latest Order
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
                <?php include 'template/footer.php';?>
        <!-- <script src="../assets/demo/chart-area-demo.js"></script> -->
        <script src="analytics/chart-sales.js"></script>
        <!-- <script src="../assets/demo/chart-bar-demo.js"></script> -->
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
                <td><span class="badge bg-${getStatusColor(order.order_status)}">${order.order_status}</span></td>
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
</script>

<script>
  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#292b2c';

  var ctx = document.getElementById("myBarChart");
  var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($monthly_labels); ?>,
      datasets: [{
        label: "Orders",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: <?= json_encode($monthly_data); ?>,
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'month'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 12
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            suggestedMax: Math.max(...<?= json_encode($monthly_data); ?>) + 5,
            maxTicksLimit: 6
          },
          gridLines: {
            display: true
          }
        }],
      },
      legend: {
        display: false
      }
    }
  });
</script>
<script>
  fetch('worker/db_site_sessions.php')
    .then(response => response.json())
    .then(data => {
      document.getElementById("session-value").textContent = data.total_sessions;
      const percentageChange = data.percentage_change;
      const changeElement = document.getElementById("session-change");
      if (percentageChange >= 0) {
        changeElement.textContent = `↑ ${percentageChange}%`;
        changeElement.classList.remove("text-danger");
        changeElement.classList.add("text-success");
      } else {
        changeElement.textContent = `↓ ${Math.abs(percentageChange)}%`;
        changeElement.classList.remove("text-success");
        changeElement.classList.add("text-danger");
      }
      document.getElementById("session-footer").textContent = `${data.today_sessions} today • ${data.yesterday_sessions} yesterday`;
    });
    fetch('worker/db_today_sales.php')
    .then(response => response.json())
    .then(data => {
      const salesValue = document.getElementById("total-sales-value");
      const salesFooter = document.getElementById("total-sales-footer");

      // Update the sales value
      salesValue.textContent = `₱${data.total_sales}`;

      // Update the footer
      if (data.today_orders > 0) {
        salesFooter.textContent = `${data.today_orders} sale(s) today`;
      } else {
        salesFooter.textContent = "No sales yet";
      }
    });

    fetch('worker/db_today_orders.php')
    .then(response => response.json())
    .then(data => {
      const ordersValue = document.getElementById("total-orders-value");
      const ordersFooter = document.getElementById("total-orders-footer");

      // Update the orders value
      ordersValue.textContent = data.today_orders;

      // Update the footer
      if (data.today_orders > 0) {
        ordersFooter.textContent = `${data.today_orders} order(s) today`;
      } else {
        ordersFooter.textContent = "No orders yet";
      }
    });

    fetch('worker/db_today_users.php')
    .then(response => response.json())
    .then(data => {
      const usersValue = document.getElementById("new-users-value");
      const usersFooter = document.getElementById("new-users-footer");

      // Update the users value
      usersValue.textContent = data.today_users;

      // Update the footer
      if (data.today_users > 0) {
        usersFooter.textContent = `${data.today_users} new user(s) today`;
      } else {
        usersFooter.textContent = "No new subscribers";
      }
    });
</script>


<script>
$(document).ready(function () {
  // Fetch current status from DB on load
  $.ajax({
    url: 'worker/shop_status_get.php',
    method: 'GET',
    success: function (response) {
      const trimmed = response.trim();
      const checkbox = $('#checkbox');
      const statusText = $('#shop-status-text');

      if (trimmed === 'online') {
        checkbox.prop('checked', true);
        statusText.text('Shop is Open').removeClass('text-muted').addClass('text-success');
      } else {
        checkbox.prop('checked', false);
        statusText.text('Shop is Closed').removeClass('text-success').addClass('text-muted');
      }
    }
  });

  // When user toggles the checkbox
  $('#checkbox').on('change', function () {
    const isChecked = $(this).is(':checked');
    const newStatus = isChecked ? 'online' : 'offline';
    const statusText = $('#shop-status-text');

    // Update UI immediately
    if (isChecked) {
      statusText.text('Shop is Open').removeClass('text-muted').addClass('text-success');
    } else {
      statusText.text('Shop is Closed').removeClass('text-success').addClass('text-muted');
    }

    // Send to server
    $.ajax({
      url: 'worker/shop_status_update.php',
      method: 'POST',
      data: { status: newStatus },
      success: function () {
        console.log('Shop status updated to: ' + newStatus);
      },
      error: function () {
        alert('Error updating shop status');
      }
    });
  });
});
</script>