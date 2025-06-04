<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['user_id'];

    // Prepare the SQL query to update the user status
$stmt = $conn->prepare("UPDATE users SET status = 'Inactive' WHERE user_id = ?");
$stmt->bind_param("i", $id);
    
// Execute the query
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User status updated to Inactive.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update user status.']);
}
}
?>