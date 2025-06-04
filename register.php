<?php
include 'header.php';
?>  
<body>

<?php
$nav_active = "";
include 'nav.php';
?>  

    
<!-- Call to Action Section -->
<section class="cta-section-register">
    <div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header text-center" style="background-color: #fff;">
                <img src="assets/img/master_sushi.jpg" alt="Master sushi logo" class="img-fluid rounded-circle register-img mt-2">
              <h3 class="font-weight-light mt-2">
                    Create Account
                </h3>
            </div>
            <div class="card-body">


    <div id="alertArea"></div> <!-- Alert Message Area -->
            <form id="registerForm">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-floating mb-3 mb-md-0">
                <input class="form-control" id="inputFirstName" name="firstname" type="text" placeholder="Enter your first name" />
                <label for="inputFirstName">First name</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input class="form-control" id="inputLastName" name="lastname" type="text" placeholder="Enter your last name" />
                <label for="inputLastName">Last name</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-floating mb-3 mb-md-0">
                <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" />
                <label for="inputEmail">Email address</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating mb-3 mb-md-0">
                <input class="form-control" id="inputContact" name="contact" type="tel" placeholder="09123456789" pattern="09[0-9]{9}" maxlength="11" minlength="11" />
                <label for="inputContact">Contact Number </label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-floating mb-3 mb-md-0">
                <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Create a password" />
                <label for="inputPassword">Password</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating mb-3 mb-md-0">
                <input class="form-control" id="inputPasswordConfirm" name="confirm_password" type="password" placeholder="Confirm password" />
                <label for="inputPasswordConfirm">Confirm Password</label>
            </div>
        </div>
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" id="inputRememberPassword" name="agreement" type="checkbox" />
        <label class="form-check-label" for="inputRememberPassword">
            <small>
                By signing up, you agree to Master Sushi’s Privacy Policy and our Website’s Terms & Conditions.
            </small>
        </label>
    </div>

    <div class="mt-4 mb-0">
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Create Account</button>
        </div>
    </div>
</form>


            </div>
            <div class="card-footer text-center py-3" style="background-color: #fff;">
                <div class="small"><a href="login.php" class="cute-color">Have an account? Go to login</a></div>
            </div>
        </div>
    </div>
</div>
  </div>

    </div>
    <br>
</section>


<?php include 'footer.php';?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>
<script>
$('#registerForm').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    $.ajax({
        url: 'register_ajax.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            let alertType = response.status === 'success' ? 'success' : 'danger';
            $('#alertArea').html(`
                <div class="alert alert-${alertType}" role="alert">
                    ${response.message}
                </div>
            `);
            if (response.status === 'success') {
                $('#registerForm')[0].reset();
                
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 1000); 
            }
        },
        error: function(xhr, status, error) {
    console.error("Response Text:", xhr.responseText); // This shows the invalid output
    $('#alertArea').html(`
        <div class="alert alert-danger" role="alert">
            An unexpected error occurred: ${error}
        </div>
    `);
}
        // error: function() {
        //     $('#alertArea').html(`
        //         <div class="alert alert-danger" role="alert">
        //             An unexpected error occurred. Please try again.
        //         </div>
        //     `);
        // }
    });
});
</script>
