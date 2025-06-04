<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand text-center" href="index.php">MASTER SUSHI</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <h3 class="text-white ms-auto me-0 me-md-3 my-2 my-md-0 d-none d-md-block">Ordering and Inventory Management System</h3>
            <!-- Navbar Search-->
            <!-- <div class="notification d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="bell-container">
                    <div class="bell"></div>
                </div>
            </div> -->

            <!-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form> -->
            <div class="ms-auto"></div>
            <!-- Navbar-->

            <!-- <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul> -->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
    <!-- Notification Dropdown -->
    <li class="nav-item dropdown me-2">
    <a class="nav-link notification" id="notificationDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="bell-container">
            <div class="bell"></div>
        </div>
        <span class="notification-count">0</span> <!-- will update dynamically -->
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationList">
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-center text-primary" href="notifications.php">View all notifications</a></li>
    </ul>
</li>

    <!-- User Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user fa-fw"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li>
        <a class="dropdown-item" href="settings.php">
            <i class="fas fa-cog me-2"></i> Settings
        </a>
    </li>
                        <?php if($global_user_role === 'Admin') { ?>
    <li>
        <a class="dropdown-item" href="activity_log.php">
            <i class="fas fa-list-alt me-2"></i> Activity Log
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="archive.php">
            <i class="fas fa-archive me-2"></i> Archived User 
        </a>
    </li>
    <?php } ?>
    <li><hr class="dropdown-divider"></li>
    <li>
        <a class="dropdown-item text-danger" href="../include/signout.php">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </li>
        </ul>
    </li>
</ul>
        </nav>

        <script>
$(document).ready(function () {
    fetchNotifications();

    function fetchNotifications() {
        $.ajax({
            url: 'worker/fetch_notifications.php',
            type: 'GET',
            dataType: 'json',
            success: function (notifications) {
                let notificationList = $('#notificationList');
                let notificationCount = $('.notification-count');

                // Clear previous dynamic notifications
                notificationList.find('.dynamic-notification').remove();

                if (notifications.length > 0) {
                    notificationCount.text(notifications.length); // Update count

                    notifications.forEach(function (notification) {
                        const listItem = `
                            <li class="dynamic-notification px-3 py-2" style="white-space: normal; ">
                                <a href="order_details.php?order_id=${notification.order_id}&notification_id=${notification.notification_id}" style="text-decoration: none; color: black;">
                                <div>
                                    🛒
                                ${notification.message}</div>
                            </a>
                            </li>
                        `;
                        $(listItem).insertBefore(notificationList.find('hr'));
                    });
                } else {
                    notificationCount.text('0');
                    const noNotif = `
                        <li class="dynamic-notification text-center py-2">
                            <span class="dropdown-item">No new notifications</span>
                        </li>
                    `;
                    $(noNotif).insertBefore(notificationList.find('hr'));
                }
            },
            error: function (xhr) {
                console.error("Error fetching notifications:", xhr.responseText);
            }
        });
    }
});
</script>