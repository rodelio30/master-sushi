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
                        <h1 class="mt-4">Notification List</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Notifications</li>
                            </ol>
                            <!-- <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Add New User
                            </button> -->
                        </div>
                       
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Notification List
                            </div>
                            <div class="card-body">
                            <table id="datatablesSimple2" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Message</th>
                                        <th>Order Tracker</th>
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



<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    $.ajax({
        url: 'worker/fetch_notification_list.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let tbody = $('#datatablesSimple2 tbody');
            tbody.empty();

            response.data.forEach(notification => {
                tbody.append(`
                    <tr>
                        <td>
                            <strong>${notification.full_name || 'Unknown User'}</strong><br>
                            ${notification.message}
                        </td>
                        <td>${notification.order_tracker}</td>
                        <td>
                        <span class="badge ${
    notification.order_status === 'Pending' ? 'bg-warning' :
    notification.order_status === 'Processing' ? 'bg-primary' :
    notification.order_status === 'Completed' ? 'bg-success' :
    notification.order_status === 'Cancelled' ? 'bg-danger' :
    'bg-secondary'
}">
    ${notification.order_status}
</span>
                        </td>
                        <td>
                            <a href="order_details.php?order_id=${notification.order_id}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                `);
            });

            const datatablesSimple2 = document.getElementById('datatablesSimple2');
            if (datatablesSimple2) {
                new simpleDatatables.DataTable(datatablesSimple2);
            }
        },
        error: function() {
            alert('Failed to load notification data.');
        }
    });
});

</script>