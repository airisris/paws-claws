<?php
  // connect to Database
  $database = connectToDB();
?>
<?php require "parts/header.php"; ?>
  <div class="row p-0 m-0 " style="height:100vh">
    <div class="col-6 container d-flex justify-content-center align-items-end">
      <img width="1000%" src="login-animal.png"/>
    </div>
    <div class="col-6 container d-flex justify-content-center align-items-center flex-column">
      <h1 class="h1 mb-4 text-center">Welcome Back!</h1>
      <!-- form -->
      <div class="card p-4 w-75">
        <!-- message -->
        <?php require "parts/message_success.php"; ?>
        <?php require "parts/message_error.php"; ?>
        <form method="POST" action="/auth/login">
          <div class="mb-3">
            <label for="email" class="visually-hidden">Email</label>
            <input type="text" class="form-control" id="email" placeholder="email@example.com" name="email"/>
          </div>
          <div class="mb-3">
            <label for="password" class="visually-hidden">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="password"/>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Log In</button>
          </div>
        </form>
      </div>
      <!-- links -->
      <div class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3 w-75">
        <a href="/home" class="text-decoration-none small">
          <i class="bi bi-arrow-left-circle"></i> Go back
        </a>
        <a href="/signup" class="text-decoration-none small">
          Don't have an account? Sign up here <i class="bi bi-arrow-right-circle"></i>
        </a>
      </div>
    </div>
  </div>
<?php require "parts/footer.php"; ?>