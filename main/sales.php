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
                        <h1 class="mt-4">Sales Overview</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Sales</li>
                            </ol>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Sales Performance
                                    </div>
                                    <!-- <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div> -->
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                          <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            All Sales
                          </div>
                          <div class="card-body">
                            <table id="datatablesSimple2" class="table table-striped">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Product Name</th>
                                  <th>Quantity Sold</th>
                                  <th>Total</th>
                                  <th>Date</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                </main>

<?php include 'template/footer.php';?>
        <script src="../assets/demo/chart-bar-demo.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="analytics/chart-sales.js"></script>

<script>
$(document).ready(function () {
  fetchSales();

  function fetchSales() {
    $.ajax({
      url: 'worker/fetch_sales.php',
      type: 'GET',
      dataType: 'json',
      success: function (sales) {
        let tbody = $('#datatablesSimple2 tbody');
        tbody.empty();

        if (sales.length > 0) {
          sales.forEach(function (sale, index) {
            tbody.append(`
              <tr>
                <td>${index + 1}</td>
                <td>${sale.product_name}</td>
                <td>${sale.quantity}</td>
                <td>₱${parseFloat(sale.total).toFixed(2)}</td>
                <td>${sale.sale_date}</td>
              </tr>
            `);
          });

          // Initialize or reinitialize the DataTable
          if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#datatablesSimple2')) {
            $('#datatablesSimple2').DataTable();
          } else if (typeof simpleDatatables !== 'undefined') {
            new simpleDatatables.DataTable(document.getElementById('datatablesSimple2'));
          }
        } else {
          tbody.append('<tr><td colspan="5" class="text-center">No sales found</td></tr>');
        }
      },
      error: function (xhr) {
        console.error("Error fetching sales:", xhr.responseText);
      }
    });
  }
});
</script>
