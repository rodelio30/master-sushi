<?php 
// Check Connections
include 'template/checker.php';

// $success = $error = '';

// if (isset($_GET['updated']) && $_GET['updated'] == 1) {
//     $success = "Profile updated successfully!";
// }
$success = '';
$error = '';

if (isset($_GET['status']) && isset($_GET['message'])) {
    if ($_GET['status'] === 'success') {
        $success = htmlspecialchars($_GET['message']);
    } elseif ($_GET['status'] === 'error') {
        $error = htmlspecialchars($_GET['message']);
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $user_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $username   = $_POST['username'];
    $email      = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address    = $_POST['address'];

    // Handle profile picture
    $profile_pic = $_FILES['profile_pic']['name'] ?? '';
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($profile_pic);


        // Input validation
    if (strlen($contact_number) > 11) {
        $msg = "Contact number must not exceed 11 characters.";
        header("Location: settings.php?status=error&message=" . urlencode($msg));
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
        header("Location: settings.php?status=error&message=" . urlencode($msg));
        exit(); 
    } else {
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, contact_number=?, address=? WHERE user_id=?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $contact_number, $address, $user_id);

        if ($stmt->execute()) {
            header("Location: settings.php?status=success&message=" . urlencode("Profile updated successfully."));
            exit();
        } else {
            header("Location: settings.php?status=error&message=" . urlencode("Something went wrong."));
            exit();
        }
        $stmt->close();
    }
    // If a new profile pic is uploaded
    // if (!empty($profile_pic)) {
    //     move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
    //     $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, contact_number=?, address=?, profile_pic=? WHERE user_id=?");
    //     $stmt->bind_param("sssssssi", $first_name, $last_name, $username, $email, $contact_number, $address, $profile_pic, $user_id);
    // } else {
    //     // If no new image uploaded
    //     $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, contact_number=?, address=? WHERE user_id=?");
    //     $stmt->bind_param("ssssssi", $first_name, $last_name, $username, $email, $contact_number, $address, $user_id);
    // }

    // if ($stmt->execute()) {
    //     echo "<script> window.location.href = 'settings.php?updated=1'; </script>";
    // } else {
    //     $error = "Failed to update profile.";
    // }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Get current hashed password from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Check if current password matches
    if (!password_verify($current_password, $hashed_password)) {
        $msg = "Current password is incorrect.";
        header("Location: settings.php?status=error&message=" . urlencode($msg));
        exit();
    }

    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        $msg = "New password and confirm password do not match.";
        header("Location: settings.php?status=error&message=" . urlencode($msg));
        exit();
    }

    // Hash new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $update_stmt->bind_param("si", $new_hashed_password, $user_id);

    if ($update_stmt->execute()) {
        header("Location: settings.php?status=success&message=" . urlencode("Password updated successfully."));
        exit();
    } else {
        header("Location: settings.php?status=error&message=" . urlencode("Failed to update password."));
        exit();
    }
    $update_stmt->close();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Line below for the code
?>
<?php include 'template/header.php'; ?>
    <body class="sb-nav-fixed">
        <?php include 'template/topbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'template/sidebar.php'; ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Settings</h1>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Settings</li>
                            </ol>
                        </div>
                       
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-gear me-1"></i>
                                Settings
                            </div>
                            <div class="card-body">

<?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
<?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form action="settings.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="update_profile" value="1">

    <div class="row mb-3">
        <div class="col-md-6">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
        </div>
        <div class="col-md-6">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Contact Number</label>
        <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($user['contact_number']) ?>">
    </div>

    <div class="mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
    </div>
    
    <!-- <div class="mb-3">
        <label>Profile Picture</label><br>
        <?php if (!empty($user['profile_pic'])): ?>
            <img src="../assets/profile/<?= htmlspecialchars($user['profile_pic']) ?>" width="100" class="mb-2"><br>
        <?php endif; ?>
        <input type="file" name="profile_pic" class="form-control">
    </div> -->

    <div class="text-center">
    <button type="submit" class="btn btn-primary">Update Profile</button>
    </div>
</form>

<hr>

<form action="settings.php" method="POST">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Current Password</label>
            <input type="text" name="current_password" class="form-control" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>New Password</label>
            <input type="text" name="new_password" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Confirm Password</label>
            <input type="text" name="confirm_password" class="form-control" required>
        </div>
    </div>

    <div class="text-center">
    <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
    </div>
</form>
                            </div>
                        </div>
                    </div>
                </main>



<?php include 'template/footer.php';?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
