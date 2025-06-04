<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $category_name = trim($_POST['category_name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if (!empty($category_id) && !empty($category_name)) {

        // Check if category name exists in another record
        $checkName = $conn->prepare("SELECT category_id FROM categories WHERE category_name = ? AND category_id != ?");
        $checkName->bind_param("si", $category_name, $category_id);
        $checkName->execute();
        $resultName = $checkName->get_result();

        if ($resultName->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Category name already exists.']);
            exit;
        }

        // Check if description exists in another record
        $checkDesc = $conn->prepare("SELECT category_id FROM categories WHERE description = ? AND category_id != ?");
        $checkDesc->bind_param("si", $description, $category_id);
        $checkDesc->execute();
        $resultDesc = $checkDesc->get_result();

        if ($resultDesc->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Category description already exists.']);
            exit;
        }

        // Proceed with update
        $stmt = $conn->prepare("UPDATE categories SET category_name = ?, description = ?, status = ? WHERE category_id = ?");
        $stmt->bind_param("sssi", $category_name, $description, $status, $category_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update category.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data provided.']);
    }
}

$conn->close();
?>