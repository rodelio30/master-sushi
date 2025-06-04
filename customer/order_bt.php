<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegant Order List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f7ff;
        }

        .order-card {
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-delivered {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-processing {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-shipped {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <header class="mb-4">
            <h1 class="display-5 fw-bold text-primary">Your Orders</h1>
            <p class="text-muted">Track and manage your recent purchases</p>
        </header>

        <div class="bg-white rounded shadow p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h5 fw-semibold text-dark">Recent Orders</h2>
                    <p class="text-muted small">Showing 5 of 12 orders</p>
                </div>
                <div class="d-flex gap-2 mt-3 mt-sm-0">
                    <button class="btn btn-primary">Filter</button>
                    <button class="btn btn-outline-secondary">Sort by: Recent</button>
                </div>
            </div>

            <!-- Order 1 -->
            <div class="order-card bg-white border rounded p-4 mb-3">
                <div class="d-flex flex-wrap justify-content-between align-items-start">
                    <div class="mb-3 mb-sm-0">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-semibold text-dark">Order #A80942</span>
                            <span class="status-badge status-delivered ms-3">Delivered</span>
                        </div>
                        <p class="text-muted small">Placed on May 24, 2023</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm">Track</button>
                        <button class="btn btn-outline-secondary btn-sm">Details</button>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-light rounded me-3" style="width: 64px; height: 64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="text-secondary" viewBox="0 0 24 24">
                            <path d="M16 11V7a4 4 0 0 0-8 0v4H5l1 12h12l1-12h-3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="fw-medium text-dark mb-0">Premium Wireless Headphones</p>
                        <p class="text-muted small mb-0">$149.99 • Qty: 1</p>
                    </div>
                </div>
            </div>

            <!-- Order 2 -->
            <div class="order-card bg-white border rounded p-4 mb-3">
                <div class="d-flex flex-wrap justify-content-between align-items-start">
                    <div class="mb-3 mb-sm-0">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-semibold text-dark">Order #B71023</span>
                            <span class="status-badge status-processing ms-3">Processing</span>
                        </div>
                        <p class="text-muted small">Placed on May 20, 2023</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm">Track</button>
                        <button class="btn btn-outline-secondary btn-sm">Details</button>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-light rounded me-3" style="width: 64px; height: 64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="text-secondary" viewBox="0 0 24 24">
                            <path d="M12 18h.01M8 21h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="fw-medium text-dark mb-0">Smartphone Case</p>
                        <p class="text-muted small mb-0">$24.99 • Qty: 1</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button class="btn btn-outline-primary d-inline-flex align-items-center">
                    View All Orders
                    <svg xmlns="http://www.w3.org/2000/svg" class="ms-2" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="bg-light rounded p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h3 class="h6 fw-semibold text-primary">Need help with an order?</h3>
                    <p class="text-primary">Our customer service team is here to help</p>
                </div>
                <button class="btn btn-primary">Contact Support</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
