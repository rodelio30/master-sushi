<?php
header('Content-Type: application/json');

define('MSmember', true); 
require('include/dbconnect.php'); 

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contact = htmlspecialchars(trim($_POST['contact']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($contact) || empty($password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    if (!preg_match('/^09[0-9]{9}$/', $contact)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid PH contact number.']);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters.']);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    if (!isset($_POST['agreement'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must agree to the Privacy Policy and Terms & Conditions.']);
        exit;
    }
        $agreement = isset($_POST['agreement']) ? 1 : 0;

    // Check if email already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email is already registered.']);
        exit;
    }



    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $username = strtolower(explode('@', $email)[0]); // optional: dynamic username based on email
    $profile_pic = 'default.png';
    $status = 'Active';

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, username, password, role, contact_number, profile_pic, status, last_login, agreement) VALUES (?, ?, ?, ?, ?, 'Customer', ?, ?, ?, NULL, ?)");
    $stmt->bind_param("ssssssssi", $firstname, $lastname, $email, $username, $hashed_password, $contact, $profile_pic, $status, $agreement);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Account created successfully!']);
    } else {
        // echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again.']);
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $stmt->error
        ]);
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>