<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modern E-commerce Product Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #ffffff;
    }

    .category-item {
      transition: all 0.3s ease;
      position: relative;
      display: block;
      font-size: 1.125rem;
      font-weight: 600;
      color: #495057;
      text-decoration: none;
    }

    .category-item:hover {
      transform: translateX(8px);
      color: #c5b87f;
    }

    .category-item::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background-color: #c5b87f;
      transition: width 0.3s ease;
    }

    .category-item:hover::after {
      width: 100%;
    }

    .product-card {
      transition: all 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .view-details-btn {
      transition: all 0.3s ease;
    }

    .view-details-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
    }

    .active-category {
      color: #3B82F6 !important;
      font-weight: 600;
    }

    .product-image-placeholder {
      height: 12rem;
      background-color: #f8f9fa;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .product-icon {
      width: 8rem;
      height: 8rem;
      color: #adb5bd;
    }
  </style>
</head>
<body>

  <div class="container py-5">
    <header class="mb-5 text-center">
      <h1 class="fw-bold text-dark">Gourmet Market</h1>
    </header>

    <div class="row g-4">
      <!-- Sidebar -->
      <div class="col-md-3">
        <div class="bg-light p-4 rounded">
          <h2 class="h5 fw-bold border-bottom pb-2 mb-4 text-dark">Category List</h2>
          <ul class="list-unstyled">
            <li><a href="#" class="category-item active-category">All Products</a></li>
            <li><a href="#" class="category-item">Dressing</a></li>
            <li><a href="#" class="category-item">Sauce</a></li>
            <li><a href="#" class="category-item">Sushi</a></li>
          </ul>
        </div>
      </div>

      <!-- Product Grid -->
      <div class="col-md-9">
        <h2 class="h4 fw-bold text-center text-dark mb-4">Available Products</h2>

        <div class="row g-4">
          <!-- Product Card 1 -->
          <div class="col-sm-6 col-lg-4">
            <div class="product-card bg-white rounded overflow-hidden shadow-sm p-3 h-100">
              <div class="product-image-placeholder mb-3">
                <svg class="product-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="fs-6 fw-bold text-dark mb-2">Japanese Soy Sauce</h3>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-danger fw-semibold">$12.99</span>
                <span class="text-muted small">Qty: 24</span>
              </div>
              <button class="btn btn-primary w-100 view-details-btn">View Details</button>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4">
            <div class="product-card bg-white rounded overflow-hidden shadow-sm p-3 h-100">
              <div class="product-image-placeholder mb-3">
                <svg class="product-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="fs-6 fw-bold text-dark mb-2">Japanese Soy Sauce</h3>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-danger fw-semibold">$12.99</span>
                <span class="text-muted small">Qty: 24</span>
              </div>
              <button class="btn btn-primary w-100 view-details-btn">View Details</button>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4">
            <div class="product-card bg-white rounded overflow-hidden shadow-sm p-3 h-100">
              <div class="product-image-placeholder mb-3">
                <svg class="product-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="fs-6 fw-bold text-dark mb-2">Japanese Soy Sauce</h3>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-danger fw-semibold">$12.99</span>
                <span class="text-muted small">Qty: 24</span>
              </div>
              <button class="btn btn-primary w-100 view-details-btn">View Details</button>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4">
            <div class="product-card bg-white rounded overflow-hidden shadow-sm p-3 h-100">
              <div class="product-image-placeholder mb-3">
                <svg class="product-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="fs-6 fw-bold text-dark mb-2">Japanese Soy Sauce</h3>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-danger fw-semibold">$12.99</span>
                <span class="text-muted small">Qty: 24</span>
              </div>
              <button class="btn btn-primary w-100 view-details-btn">View Details</button>
            </div>
          </div>

          <!-- Repeat Product Cards as needed, change content -->

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>