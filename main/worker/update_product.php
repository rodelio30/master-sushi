<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $product_name = trim($_POST['product_name']);
    $category_id = $_POST['category_id'];
    $description = trim($_POST['description']);
    $buying_price = $_POST['buying_price'];
    $selling_price = $_POST['selling_price'];
    $brand = trim($_POST['brand']);
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    // Check if product name already exists (excluding this product)
    $checkName = $conn->prepare("SELECT product_id FROM products WHERE product_name = ? AND product_id != ?");
    $checkName->bind_param("si", $product_name, $product_id);
    $checkName->execute();
    $checkResult = $checkName->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product name already exists.']);
        exit;
    }
    $checkName->close();

    // Update the product
    $stmt = $conn->prepare("UPDATE products 
        SET product_name = ?, category_id = ?, description = ?, buying_price = ?, selling_price = ?, brand = ?, quantity = ?, status = ?
        WHERE product_id = ?");
    $stmt->bind_param("sissdsssi", $product_name, $category_id, $description, $buying_price, $selling_price, $brand, $quantity, $status, $product_id);

    if ($stmt->execute()) {
        // ✅ Handle image upload
        if (!empty($_FILES['product_images']['name'][0])) {
            // Delete old images
            $deleteStmt = $conn->prepare("DELETE FROM product_image WHERE product_id = ?");
            $deleteStmt->bind_param("i", $product_id);
            $deleteStmt->execute();
            $deleteStmt->close();

            // Upload new images
            foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                $originalName = basename($_FILES['product_images']['name'][$key]);
                $uniqueName = uniqid() . '_' . $originalName;
                $uploadDir = '../../assets/uploads/products/';
                $uploadPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($tmp_name, $uploadPath)) {
                    $imagePath = 'assets/uploads/products/' . $uniqueName;
                    $imageStmt = $conn->prepare("INSERT INTO product_image (product_id, image_path) VALUES (?, ?)");
                    $imageStmt->bind_param("is", $product_id, $imagePath);
                    $imageStmt->execute();
                    $imageStmt->close();
                }
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Product updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update product.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

?>