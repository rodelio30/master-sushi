<?php
include 'header.php';

// Initialize visibility states
$hidden_check = isset($_SESSION['hidden_check']) ? $_SESSION['hidden_check'] : 'block';
$hidden_insert = isset($_SESSION['hidden_insert']) ? $_SESSION['hidden_insert'] : 'none';
// $ses_id_no = isset($_SESSION['ses_id_no']) ? $_SESSION['ses_id_no'] : '';

if (isset($_POST["submit_admin"])) {
    $email = $_POST['email'];

    // Check if email exists in users table
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();
    } else {
        // If not found in users, check in users table
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($email);
            $stmt->fetch();
        } else {
            // Email not found in both tables
            echo "<script>alert('No user data found for this Email'); document.location='password.php';</script>";
            exit();
        }
    }

    // If email is found in either table, proceed
    echo "<script>alert('User Found!'); document.location='password.php';</script>";
    $_SESSION['hidden_check'] = 'none';
    $_SESSION['hidden_insert'] = 'block';
    $_SESSION['ses_email'] = $email;
}

if (isset($_POST["submit_update"])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($password) || empty($confirm_password) || $password !== $confirm_password) {
        echo "<script>alert('Passwords do NOT match or are empty!');</script>";
        $_SESSION['hidden_check'] = 'none';
        $_SESSION['hidden_insert'] = 'block';
    } else {
        $_SESSION['hidden_check']  = NULL;
        $_SESSION['hidden_insert'] = NULL;

        $email = $_SESSION['ses_email'];

        $update_password = md5($password); // Hash password

        mysqli_query($conn, "UPDATE users SET password = '$update_password' WHERE email = '$email'")
            or die("Error updating password...");

        echo "<script>alert('Password Changed!'); document.location='login.php';</script>";
        $_SESSION['ses_email'] = NULL;
        exit();
    }
}
?>  
<body>

<?php
$nav_active = "";
include 'nav.php';
?>  

    
<!-- Call to Action Section -->
<section class="cta-section-register">
  <!-- <div class="container"> -->
    <div class="container" style="height: 80dvh">
  <div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header text-center" style="background-color: #fff;">
              <img src="assets/img/master_sushi.jpg" alt="Master sushi logo" class="img-fluid rounded-circle login-img">
              <h3 class="font-weight-light my-4">Password Recovery</h3></div>


              <div id="alertArea" class="p-3 pb-0"></div>
<!-- Email Check Form -->
<div class="card-body" id="checkEmailForm" style="display: <?= $hidden_check ?>;">
    <div class="small mb-3 text-muted">
        Please enter your email address, and we’ll check if it exists in our system to help you reset your password.
    </div>
    <form id="emailCheckForm">
        <div class="form-floating mb-3">
            <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" required />
            <label for="inputEmail">Email address</label>
        </div>
        <div class="text-center mt-4 mb-0">
            <button type="submit" class="btn btn-sm btn-primary">Check</button>
        </div>
    </form>
</div>

<!-- New Password Form -->
<div class="card-body" id="resetPasswordForm" style="display: <?= $hidden_insert ?>;">
    <div class="small mb-3 text-muted">
        We’ve verified your email. You may now set a new password.
    </div>
    <form id="updatePasswordForm">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-floating mb-3 mb-md-0">
                    <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Create a password" required />
                    <label for="inputPassword">Password</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3 mb-md-0">
                    <input class="form-control" id="inputPasswordConfirm" name="confirm_password" type="password" placeholder="Confirm password" required />
                    <label for="inputPasswordConfirm">Confirm Password</label>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 mb-0">
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>
    </form>
</div>
            <div class="card-footer text-center py-3" style="background-color: #fff;">
                <div class="small"><a href="login.php" class="forgot-password">Return to login</a></div>
            </div>
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
<script>
function showAlert(type, message) {
    $('#alertArea').html(`
        <div class="alert alert-${type}" role="alert">${message}</div>
    `);
}

$('#emailCheckForm').on('submit', function(e) {
    e.preventDefault();

    $.post('password_ajax.php', {
        action: 'check_email',
        email: $('#inputEmail').val()
    }, function(response) {
        if (response.status === 'success') {
            showAlert('success', response.message);
            $('#checkEmailForm').hide();
            $('#resetPasswordForm').show();
        } else {
            showAlert('danger', response.message);
        }
    }, 'json');
});

$('#updatePasswordForm').on('submit', function(e) {
    e.preventDefault();

    $.post('password_ajax.php', {
        action: 'update_password',
        password: $('#inputPassword').val(),
        confirm_password: $('#inputPasswordConfirm').val()
    }, function(response) {
        if (response.status === 'success') {
            showAlert('success', response.message);
            setTimeout(() => window.location.href = 'login.php', 1500);
        } else {
            showAlert('danger', response.message);
        }
    }, 'json');
});
</script>