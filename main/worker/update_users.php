<?php
define('MSmember', true); 
require('../../include/dbconnect.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$user_id = $_POST['user_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$role = $_POST['role'];
$address = $_POST['address'];
$contact = $_POST['contact_number'];

$sql = "UPDATE users SET 
    first_name = ?, 
    last_name = ?, 
    username = ?, 
    email = ?, 
    role = ?, 
    address = ?, 
    contact_number = ?
    WHERE user_id = ?";

$stmt = $conn->prepare($sql);
if ($stmt->execute([$first_name, $last_name, $username, $email, $role, $address, $contact, $user_id])) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
}
}
?>
