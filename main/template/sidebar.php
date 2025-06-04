<div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <img src="../assets/img/master_sushi.jpg" alt="Master sushi Icon" class="img-fluid rounded-circle" style="height: 5rem; width: 5rem; margin: 0 auto;">
                            <div class="sb-sidenav-menu-heading">Admin Panel</div>
                            <!-- Dashboard -->
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                                Dashboard
                            </a>

                            <!-- User Management -->
                             <?php if($global_user_role === 'Admin') { ?>
                            <div class="sb-sidenav-menu-heading">Management</div>
                            <a class="nav-link" href="users.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Users 
                            </a>

                            <!-- Inventory Management -->
                            <!-- <a class="nav-link" href="inventory.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                                Inventory
                            </a> -->

                            <!-- Sales Monitoring -->
                            <!-- <a class="nav-link" href="sales.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                                Sales
                            </a> -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                                Sales
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="sales.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                                    Sales Overview
                                </a>
                                <a class="nav-link" href="sales_report.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                    Sales Report
                                </a>
                                </nav>
                            </div>
                             <?php } ?>

                            <!-- Product Management -->
                            <div class="sb-sidenav-menu-heading">Store</div>
                            <a class="nav-link" href="products.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-utensils"></i></div>
                                Products
                            </a>
                            <a class="nav-link" href="categories.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                                Categories
                            </a>

                            <!-- Order Tracking -->
                            <div class="sb-sidenav-menu-heading">Operations</div>
                            <a class="nav-link" href="orders.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                                Orders
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div id="ph-time" class="fw-bold"></div> <!-- Time will be displayed here -->
                        <hr>
                        <div class="small">Logged in as:
                        <?= $_SESSION['user_role']; ?>
                        </div>
                    </div>
                </nav>
            </div>
<script>
    function updatePhilippineTime() {
        let options = { 
            timeZone: "Asia/Manila", 
            hour12: true, 
            weekday: "long", 
            year: "numeric", 
            month: "long", 
            day: "numeric", 
            hour: "numeric", 
            minute: "numeric", 
            second: "numeric" 
        };
        let phTime = new Date().toLocaleString("en-US", options);
        document.getElementById("ph-time").innerHTML = `<i class="fas fa-clock"></i> ${phTime}`;
    }

    // Update the time every second
    setInterval(updatePhilippineTime, 1000);
    updatePhilippineTime(); // Initial call
</script>