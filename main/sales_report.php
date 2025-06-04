<?php 
// Check Connections
include 'template/checker.php';

// Line below for the code
?>
<?php include 'template/header.php'; 
?>
<style>
  tfoot {
  display: table-footer-group;
}
</style>
    <body class="sb-nav-fixed">
        <?php include 'template/topbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'template/sidebar.php'; ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Sales Report</h1>
                        <!-- <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Sales Report</li>
                            </ol>
                        </div> -->
                        <div class="card mb-4">
                          <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Filter Sales Report 
                          </div>
                          <div class="card-body">
                          <form id="salesFilterForm" class="row g-3 mb-4">
  <div class="col-md-4">
    <label for="fromDate" class="form-label">From:</label>
    <input type="date" class="form-control" id="fromDate" name="fromDate" required>
  </div>
  <div class="col-md-4">
    <label for="toDate" class="form-label">To:</label>
    <input type="date" class="form-control" id="toDate" name="toDate" required>
  </div>
  <div class="col-md-4 align-self-end">
    <button type="submit" class="btn btn-primary">Generate Report</button>
  </div>
</form>

<!-- Sales Table Result -->
<div id="salesReportResult">
  <table class="table table-bordered table-striped display" id="salesReportTable" style="display: none;">
    <thead>
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Product Name</th>
        <th>Buying Price</th>
        <th>Selling Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Profit</th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <th colspan="6"  style="text-align:right"><strong>Grand Total:</strong></th>
        <th id="grandTotal">₱0.00</th>
        <th id="grandProfit">₱0.00</th>
      </tr>
    </tfoot>
  </table>
</div>
                          </div>
                        </div>
                    </div>
                </main>

<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables 2.x Core CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

<!-- Buttons extension for DataTables 2.x -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>

<!-- Print button -->
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
  $('#salesFilterForm').on('submit', function (e) {
    e.preventDefault();

    const from = $('#fromDate').val();
    const to = $('#toDate').val();

    $.ajax({
      url: 'worker/fetch_sales_report.php',
      method: 'POST',
      data: { from, to },
      dataType: 'json',
      success: function (sales) {
        const tbody = $('#salesReportTable tbody');
        let grandTotal = 0;
        let grandProfit = 0;

        tbody.empty();

        if (sales.length > 0) {
          $('#salesReportTable').show();
          sales.forEach((sale, index) => {
            const total = sale.selling_price * sale.quantity;
            const profit = (sale.selling_price - sale.buying_price) * sale.quantity;
            grandTotal += total;
            grandProfit += profit;

            tbody.append(`
              <tr>
                <td>${index + 1}</td>
                <td>${sale.sale_date}</td>
                <td>${sale.product_name}</td>
                <td>₱${parseFloat(sale.buying_price).toFixed(2)}</td>
                <td>₱${parseFloat(sale.selling_price).toFixed(2)}</td>
                <td>${sale.quantity}</td>
                <td>₱${total.toFixed(2)}</td>
                <td>₱${profit.toFixed(2)}</td>
              </tr>
            `);
          });

         // Update footer values
//   $('#grandTotal').text(`₱${grandTotal.toFixed(2)}`);
//   $('#grandProfit').text(`₱${grandProfit.toFixed(2)}`);

//   // THEN initialize or reinitialize the DataTable
//   const datatablesSimple = document.getElementById('salesReportTable');
// if (datatablesSimple && !datatablesSimple.classList.contains("dataTable-table")) {
//     new simpleDatatables.DataTable(datatablesSimple);
// }

new DataTable('#salesReportTable', {
  perPage: 10, // 👈 This sets 10 rows per page

  footerCallback: function (row, data, start, end, display) {
    let api = this.api();

    // Converts a string like "₱1,234.56" to a number
    let parseMoney = function (i) {
      return typeof i === 'string'
        ? parseFloat(i.replace(/[₱,]/g, '')) || 0
        : typeof i === 'number'
        ? i
        : 0;
    };

    // Column index for Total and Profit
    const totalIndex = 6;
    const profitIndex = 7;

    let pageTotal = api
      .column(totalIndex, { page: 'current' })
      .data()
      .reduce((a, b) => parseMoney(a) + parseMoney(b), 0);

    let pageProfit = api
      .column(profitIndex, { page: 'current' })
      .data()
      .reduce((a, b) => parseMoney(a) + parseMoney(b), 0);

    // Update footer cells
    api.column(totalIndex).footer().innerHTML = `<u>₱${pageTotal.toFixed(2)}</u>`;
    api.column(profitIndex).footer().innerHTML = `<u>₱${pageProfit.toFixed(2)}</u>`;
  },
  layout: {
    topStart: {
      buttons: [
        {
          extend: 'print',
          text: 'Print',
          title: 'Sales Report', // ✅ This changes the print title
          messageTop: '', // ❌ This removes the default page header
          customize: function (win) {
  const body = win.document.body;
  body.style.fontSize = '12px';

  // Remove default title if present
  const title = body.querySelector('h1');
  if (title) title.remove();

  // Create custom title
  const customTitle = win.document.createElement('div');
  customTitle.innerHTML = 'Sales Report';
  customTitle.style.fontSize = '24px'; // Make it big
  customTitle.style.fontWeight = 'bold';
  customTitle.style.textAlign = 'left'; // Align left
  customTitle.style.marginBottom = '20px';

  // Insert it at the top of the body
  body.insertBefore(customTitle, body.firstChild);
}
        }
      ]
    }
  }
});


        } else {
          $('#salesReportTable').show();
          tbody.append('<tr><td colspan="9" class="text-center">No sales found in this range.</td></tr>');
          $('#grandTotal, #grandProfit').text("₱0.00");
        }
      },
      error: function (xhr) {
        console.error('Error loading sales report:', xhr.responseText);
      }
    });
  });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fromDate').setAttribute('max', today);
    document.getElementById('toDate').setAttribute('max', today);
  });
</script>