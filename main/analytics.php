<?php 
// Check Connections
include 'template/checker.php';


// 1. Top-Selling Products
$sql_top_selling = "
  SELECT 
    s.product_id,
    s.product_name,
    SUM(s.quantity) AS total_quantity_sold,
    SUM(s.total) AS total_sales
  FROM sales s
  GROUP BY s.product_id, s.product_name
  ORDER BY total_quantity_sold DESC
  LIMIT 5
";
$result_top_selling = $conn->query($sql_top_selling);

// 2. Latest Sales
$sql_latest_sales = "
  SELECT 
    s.sale_id,
    s.product_name,
    s.quantity,
    s.total,
    s.sale_date
  FROM sales s
  ORDER BY s.sale_date DESC
  LIMIT 5
";
$result_latest_sales = $conn->query($sql_latest_sales);

// 3. Recently Added Products
$sql_recent_products = "
  SELECT 
    p.product_name,
    c.category_name,
    p.selling_price,
    p.created_at
  FROM products p
  LEFT JOIN categories c ON p.category_id = c.category_id
  ORDER BY p.created_at DESC
  LIMIT 5
";
$result_recent_products = $conn->query($sql_recent_products);
?>
<?php include 'template/header.php'; ?>
<style>
</style>
    <body class="sb-nav-fixed">
        <?php include 'template/topbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'template/sidebar.php'; ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Analytics</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Analytics</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area me-1"></i>
                                Overview
                            </div>
                            <div class="card-body">
                        <div class="mt-2">
  <div class="row">

    <!-- Top Selling Products -->
    <div class="col-md-4">
      <div class="card mb-4 shadow-sm">
        <div class="card-header">
          <strong>
            <!-- <i class="bi bi-star-fill"></i>  -->
            <i class="fas fa-star me-1"></i> <!-- Products -->
            Top-Selling Products</strong>
            <!-- <div class="float-end">
              <a href="analytics.php" class="text-dark">View</a>
            </div> -->
        </div>
        <div class="card-body p-0">
          <table class="table table-bordered table-striped mb-0">
            <thead>
              <tr>
                <th>Product</th>
                <th>Qty Sold</th>
                <th>₱ Sales</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $result_top_selling->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['total_quantity_sold'] ?></td>
                <td><?= number_format($row['total_sales'], 2) ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Latest Sales -->
    <div class="col-md-4">
      <div class="card mb-4 shadow-sm">
        <div class="card-header">
          <strong>
          <i class="fas fa-cash-register me-1"></i> <!-- Sales -->
          Latest Sales</strong>
          <div class="float-end">
              <a href="sales.php" class="text-dark">View</a>
            </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-bordered table-striped mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>₱</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; while($row = $result_latest_sales->fetch_assoc()): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= number_format($row['total'], 2) ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Recently Added Products -->
    <div class="col-md-4">
      <div class="card mb-4 shadow-sm">
        <div class="card-header">
          <strong>
            <!-- <i class="bi bi-plus-circle"></i>  -->
            <i class="fas fa-box me-1"></i> <!-- Products -->
          Recently Added Products</strong>
          <div class="float-end">
              <a href="products.php" class="text-dark">View</a>
            </div>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            <?php while($row = $result_recent_products->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-bold"><?= htmlspecialchars($row['product_name']) ?></div>
                <small class="text-muted"><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></small>
              </div>
              <span class="badge bg-secondary rounded-pill">₱<?= number_format($row['selling_price'], 2) ?></span>
            </li>
            <?php endwhile; ?>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>
                      
                       
                            
                            </div>
                        </div>
                    </div>
                </main>
                <?php include 'template/footer.php';?>
        <!-- <script src="../assets/demo/chart-area-demo.js"></script> -->
        <script src="analytics/chart-sales.js"></script>
        <!-- <script src="../assets/demo/chart-bar-demo.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
