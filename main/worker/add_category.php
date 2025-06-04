<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if (empty($category_name)) {
        echo json_encode(['status' => 'error', 'message' => 'Category name is required.']);
        exit;
    }

    // Check for duplicate category name
    $checkName = $conn->prepare("SELECT * FROM categories WHERE category_name = ?");
    $checkName->bind_param("s", $category_name);
    $checkName->execute();
    $resultName = $checkName->get_result();

    if ($resultName->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Category name already exists.']);
        exit;
    }

    // Check for duplicate description
    $checkDesc = $conn->prepare("SELECT * FROM categories WHERE description = ?");
    $checkDesc->bind_param("s", $description);
    $checkDesc->execute();
    $resultDesc = $checkDesc->get_result();

    if ($resultDesc->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Category description already exists.']);
        exit;
    }

    // Proceed with insert
    $stmt = $conn->prepare("INSERT INTO categories (category_name, description, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $category_name, $description, $status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert category.']);
    }

    $stmt->close();
}
?>
