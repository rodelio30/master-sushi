<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];

    if (!empty($category_id)) {
        $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert user.']);
        }

        $stmt->close();
    } else {
        echo "Invalid ID.";
    }
}

$conn->close();
?>