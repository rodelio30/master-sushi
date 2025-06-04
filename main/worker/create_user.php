<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $contact = $_POST['contact_number'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $password = $_POST['password'];  // Get the password from the form

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the user data
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, role, contact_number, address, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Check for errors in preparing the query
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }
    
    // Bind the parameters to the SQL query
    $stmt->bind_param("sssssssss", $fname, $lname, $username, $email, $hashed_password, $role, $contact, $address, $status);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert user.']);
    }
}
?>
