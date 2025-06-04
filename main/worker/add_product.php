<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $category_id = $_POST['category_id'];
    $description = trim($_POST['description']);
    $buying_price = $_POST['buying_price'];
    $selling_price = $_POST['selling_price'];
    $brand = trim($_POST['brand']);
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    // Check if product name already exists
    $check = mysqli_query($conn, "SELECT * FROM products WHERE product_name = '$product_name'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product name already exists.']);
        exit;
    }

    $sql = "INSERT INTO products 
    (category_id, product_name, description, buying_price, selling_price, brand, quantity, status)
    VALUES 
    ('$category_id', '$product_name', '$description', '$buying_price', '$selling_price', '$brand', '$quantity', '$status')";

    if (mysqli_query($conn, $sql)) {
        $product_id = mysqli_insert_id($conn);

        // Handle image upload
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = '../../assets/uploads/products/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $fileName = basename($_FILES['images']['name'][$key]);
                $targetPath = $uploadDir . time() . '_' . $fileName;

                if (move_uploaded_file($tmp_name, $targetPath)) {
                    $relativePath = str_replace('../../', '', $targetPath);
                    $insertImg = "INSERT INTO product_image (product_id, image_path) VALUES ('$product_id', '$relativePath')";
                    mysqli_query($conn, $insertImg);
                }
            }
        }

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'SQL Error: ' . mysqli_error($conn)]);
    }
}

?>