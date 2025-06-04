<?php
header('Content-Type: application/json');

define('MSmember', true); 
require('include/dbconnect.php'); 


$action = $_POST['action'] ?? '';
$response = [];

if ($action === 'check_email') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email field is required.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['ses_email'] = $email;
        echo json_encode(['status' => 'success', 'message' => 'Email verified! You may now set a new password.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No user found with that email.']);
    }
    exit;
}

if ($action === 'update_password') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_SESSION['ses_email'] ?? '';

    if (empty($password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill out both password fields.']);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please try again.']);
        exit;
    }

    // $hashed_password = md5($password);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        unset($_SESSION['ses_email']);
        echo json_encode(['status' => 'success', 'message' => 'Password has been updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password. Please try again.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);

?>