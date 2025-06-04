<?php
include 'header.php';


// Assume user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: ../include/signout.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

if (isset($_GET['status']) && isset($_GET['message'])) {
    if ($_GET['status'] === 'success') {
        $success = htmlspecialchars($_GET['message']);
    } elseif ($_GET['status'] === 'error') {
        $error = htmlspecialchars($_GET['message']);
    }
}

// Clear messages after displaying
unset($_SESSION['success'], $_SESSION['error']);

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_details'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

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
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, contact_number=?, address=? WHERE user_id=?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $username, $email, $contact_number, $address, $user_id);

        if ($stmt->execute()) {
          $_SESSION['success'] = "Profile updated successfully!";
          header("Location: settings.php"); // reload with redirect
          exit();
      } else {
          $_SESSION['error'] = "Failed to update profile.";
          header("Location: settings.php");
          exit();
      }
    }
}

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


// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>  

<style>

#productSection {
min-height: 100vh;
/* background-color: #fff; */
background: url('../assets/img/bg-cta.png') no-repeat center center/cover;
border-radius: 12px;
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
padding: 2rem 8rem;
box-sizing: border-box;
}

.card {
  padding: 2rem;
}
  
  </style>
<body>

<?php
$nav_active = "";
include 'nav.php';
?>  
  
<section id="productSection">
  <div class="container">
    <!-- Main Content -->
    <div class="mt-2">
        <h2 style="margin-left: 1rem; margin-bottom: 20px; font-weight: bold;">Settings</h2>
              <div class="card">
        <div class="card-body">
                  <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

  <h3><b>Profile</b></h3>
  <form method="POST">
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
      <textarea name="address" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
    </div>

    <div class="text-center">
      <button type="submit" name="update_details" class="btn btn-primary">Update Profile</button>
    </div>
  </form>
  <hr>
  <h3><b>Change Password</b></h3>

<form action="settings.php" method="POST">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
    </div>

    <div class="text-center">
    <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
    </div>
</form>
</div>

    </div>
  </div>
  </div>
</section>
<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>
