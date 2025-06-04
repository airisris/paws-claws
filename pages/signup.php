<?php
    // Connect to Database
    $database = connectToDB();
?>
<?php require "parts/header.php"; ?>
  <div class="row p-0 m-0 " style="height:100vh">
    <div class="col-6 container d-flex justify-content-center align-items-center">
      <img width="1000%" src="signup-animal.png"/>
    </div>
    <div class="col-6 container d-flex justify-content-center align-items-center flex-column">
      <h1 class="h1 mb-4 text-center">Create An Account!</h1>
      <div class="card p-4 w-75">
        <!-- message -->
        <?php require "parts/message_success.php"; ?>
        <?php require "parts/message_error.php"; ?>
        <!-- start form -->
        <form method="POST" action="/auth/signup">
          <!-- name field -->
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" />
          </div>
          <!-- email field -->
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" />
          </div>
          <!-- password field -->
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password"/>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password"/>
          </div>
          <!-- sign up button -->
          <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-fu">
              Sign Up
            </button>
          </div>
        </form>
        <!-- end form -->
      </div>
      <!-- links -->
      <div class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3 w-75">
        <a href="/home" class="text-decoration-none small">
          <i class="bi bi-arrow-left-circle"></i> Go back
        </a>
        <a href="/login" class="text-decoration-none small">
          Already have an account? Log in here <i class="bi bi-arrow-right-circle"></i>
        </a>
      </div>
    </div>
  </div>
<?php require "parts/footer.php"; ?>