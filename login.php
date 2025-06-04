<?php
// Do not exchange the code below
include 'header.php';
// Do not exchange the code below

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login_submit"])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
        header("Location: login.php"); // back to login
        exit();
    }

    // Prepare statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT user_id, email, password, role, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            if ($user['status'] === 'Active') {
                // Set session values
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // Update last_login
                $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $update->bind_param("i", $user['user_id']);
                $update->execute();

                 // Success message
                 $_SESSION['success'] = "Welcome back, " . $user['email'] . "!";
                sleep(1);

                // Redirect based on role
                if ($user['role'] === 'Admin') {
                    header("Location: main/index.php");
                } elseif ($user['role'] === 'Staff') {
                    header("Location: main/index.php");
                } 
                else {
                    header("Location: customer/index.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Your account is not active.";
            }
        } else {
            $_SESSION['error'] = "Incorrect password.";
            // $entered_password = password_hash($password, PASSWORD_DEFAULT);
            // $entered_password = $password_hash($password);
            // $_SESSION['error'] = "Incorrect password. " . $user['password'] . " <br> " . $entered_password;
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
    }

    header("Location: login.php");
    exit();
}

?>  
<body>

<?php
$nav_active = "";
include 'nav.php';
?>  

    
<!-- Call to Action Section -->
<section class="cta-section-login">
  <div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header text-center" style="background-color: #fff;">
              <img src="assets/img/master_sushi.jpg" alt="Master sushi logo" class="img-fluid rounded-circle login-img">
              <h3 class="font-weight-light my-4">Sign in with your account </h3></div>
            <div class="card-body">
                <!-- Success Message -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- Error Message -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
                <form method="POST" action="login.php">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" autofocus />
                        <label for="inputEmail">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" />
                        <label for="inputPassword">Password</label>
                    </div>
                   
                        <div class="d-grid gap-2 col-6 mx-auto">
                          <button class="btn btn-primary" name="login_submit" type="submit">Login</button>
                          <a class="small forgot-password mt-3" href="password.php">Forgot Password?</a>
                        </div>
                </form>
            </div>
            <div class="card-footer text-center py-3" style="background-color: #fff;">
                <div class="small"><a href="register.php" class="forgot-password">Need an account? Sign up!</a></div>
            </div>
        </div>
    </div>
</div>
  </div>
</section>


<?php include 'footer.php';?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</html>