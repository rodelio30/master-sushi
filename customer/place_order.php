<?php
define('MSmember', true); 
require_once '../include/dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get and validate form data
$fullname = trim($_POST['fullname']);
$address = trim($_POST['address']);
$payment_method = trim($_POST['payment_method']);

// Get pickup date and time from form
$pickup_date = $_POST['pickup_date'];
$pickup_time = $_POST['pickup_time'];

$transaction_type = 'Pick Up'; // Static value as per your current setup
$total_amount = floatval($_POST['total']);

// Get user email and phone from `users` table
$userQuery = $conn->prepare("SELECT email, contact_number FROM users WHERE user_id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();
$customer_email = $userData['email'];
$customer_phone = $userData['contact_number'];
$order_tracker = 'MS-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);



// Start transaction
$conn->begin_transaction();

try {
    // Insert into `orders` table
    $orderStmt = $conn->prepare("
        INSERT INTO orders (
            order_tracker,user_id, customer_name, customer_email, customer_phone, customer_address,
            total_amount, payment_method, order_status, created_at, pickup_date, pickup_time
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW(), ?, ?)
    ");
    $orderStmt->bind_param("sissssdsss", $order_tracker, $user_id, $fullname, $customer_email, $customer_phone, $address, $total_amount, $payment_method, $pickup_date, $pickup_time);
    $orderStmt->execute();

    $order_id = $conn->insert_id;

    $message = "New Order placed by " . $fullname; // Example message

    $notif_stmt = $conn->prepare("INSERT INTO order_notifications (message, order_id) VALUES (?, ?)");
    $notif_stmt->bind_param("si", $message, $order_id);
    $notif_stmt->execute();

    // Fetch cart items
    $cartStmt = $conn->prepare("
        SELECT c.product_id, c.quantity, p.selling_price 
        FROM cart c 
        JOIN products p ON c.product_id = p.product_id 
        WHERE c.user_id = ?
    ");
    $cartStmt->bind_param("i", $user_id);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();

    // Insert into `order_items` table
    $itemStmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
        VALUES (?, ?, ?, ?, ?)
    ");

    while ($item = $cartResult->fetch_assoc()) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['selling_price'];
        $subtotal = $quantity * $price;

        $itemStmt->bind_param("iiidd", $order_id, $product_id, $quantity, $price, $subtotal);
        $itemStmt->execute();

        // Fetch product name and additional details
    $productQuery = $conn->prepare("SELECT product_name, brand FROM products WHERE product_id = ?");
    $productQuery->bind_param("i", $product_id);
    $productQuery->execute();
    $productResult = $productQuery->get_result();
    $productData = $productResult->fetch_assoc();

    $productName = $productData['product_name'];
    $details = "Brand: {$productData['brand']}";

    // Push into orderItems array
    $orderItems[] = [
        'name' => $productName,
        'details' => $details,
        'qty' => $quantity,
        'price' => $subtotal
    ];
    }

    // Send Email

    // Send Order Details to the admin's email
$to_receiver = "mastersushifs@gmail.com";
$subject = "New Order";
// $message = "";
$message = "
<div style='font-family: Arial, sans-serif; padding: 20px;'>
    <h2 style='color: #333;'>🛒 New Order Received</h2>
    <p><strong>Order Tracker:</strong> $order_tracker</p>
    <hr>

    <h3>👤 Customer Details</h3>
    <p><strong>Name:</strong> $fullname</p>
    <p><strong>Email:</strong> $customer_email</p>
    <p><strong>Phone:</strong> $customer_phone</p>
    <p><strong>Address:</strong> $address</p>

    <h3>📦 Order Info</h3>
    <p><strong>Transaction Type:</strong> $transaction_type</p>
    <p><strong>Pickup Date:</strong> $pickup_date</p>
    <p><strong>Pickup Time:</strong> $pickup_time</p>
    <p><strong>Payment Method:</strong> $payment_method</p>
    <p><strong>Total Amount:</strong> <strong style='color: #27ae60;'>₱" . number_format($total_amount, 2) . "</strong></p>
    <p><strong>Status:</strong> Pending</p>

    <h3 style='margin-top: 30px;'>Order Details</h3>
    <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
";

// Loop through order items (assuming you already have $orderItems array)
foreach ($orderItems as $item) {
    $message .= "
        <tr style='border-bottom: 1px solid #eee;'>
            <td style='padding: 10px 0;'>
                <strong>{$item['name']}</strong><br>
                <span style='color: #555;'>{$item['details']}</span><br>
                <span>Qty: {$item['qty']}</span>
            </td>
            <td style='text-align: right; vertical-align: top; font-weight: bold;'>
                ₱" . number_format($item['price'], 2) . "
            </td>
        </tr>
    ";
}

$message .= "
    </table>
    <hr style='margin-top: 40px;'>
    <p style='font-size: 13px; color: #888;'>This is an automated notification from Master Sushi Food Kiosk.</p>
</div>
";
$headers = "mastersushifs@gmail.com";

include '../mail/send_email.php';
    // Send Email

    // Clear cart
    $clearCartStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clearCartStmt->bind_param("i", $user_id);
    $clearCartStmt->execute();

    $conn->commit();


    // Redirect to a success page
    header("Location: order_success.php?order_tracker=" . $order_tracker);
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "Something went wrong: " . $e->getMessage();
}
?>
