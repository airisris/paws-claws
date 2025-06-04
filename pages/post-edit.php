<?php
  // connect to database
  $database = connectToDB();

  // get data
  $id = $_GET["id"];

  // load categories
  $sql = "SELECT * FROM categories";
  $query = $database->prepare($sql);
  $query->execute();
  $categories = $query->fetchAll();

  // load post
  $sql = "SELECT * FROM posts WHERE id = :id";

  // if user not logged in, cannot access this page
  if (!isUserLoggedIn()) {
    $_SESSION["error"] = "Please log in to access this page.";
    header("Location: /");
    exit; 
  }  

  $query = $database->prepare( $sql );
  $query->execute([
    "id" => $id
  ]);
  $post = $query->fetch();
?>
<?php require "parts/header.php"; ?>
<form method="POST" action="/post/edit" enctype="multipart/form-data">
<div class="row m-0 p-0" style="height:100vh;">
  <div class="col-4 flex-column d-flex justify-content-center align-items-end">
    <div class="d-flex justify-content-center align-content-center mb-3" style="width:18rem;">
      <!-- message -->
      <?php require "parts/message_success.php"; ?>
      <?php require "parts/message_error.php"; ?>
    </div>
    <!-- name field -->
    <div class="d-flex justify-content-center align-content-center gap-3">
      <h4 class="p-0 m-0">Name:</h4>
      <input type="text" class="form-control w-50 mb-3 border-success" id="post-name" name="name" value="<?= $post["name"]; ?>"/>
    </div>
    <!-- category field -->
    <div class="d-flex justify-content-center align-content-center gap-3 w-75">
      <h4 class="p-0 m-0">Animal:</h4>
      <select class="form-control w-50 mb-3 border-success" id="post-category" name="category_id" disabled>
        <?php foreach ($categories as $category) : ?>
        <option value="<?= $category["id"]; ?>" <?= ($post["category_id"] == $category["id"] ? "selected" : ""); ?>><?= $category["name"]; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <!-- image field -->
    <div class="card mb-3" style="width: 18rem;height: 5rem;">
      <div class="d-flex justify-content-center align-items-center h-100 w-100 ms-4">
          <img src="/<?= $post["image"]; ?>" class="img-fluid" hidden/>
        <input type="file" name="image" accept="image/*">
      </div>
    </div>
    <!-- update button -->
    <div class="mt-3" style="margin-right:80px;">
      <input type="hidden" name="id" value="<?= $post["id"]; ?>">
      <button class="btn btn-success py-3 px-4 rounded-5 m-0"><h3 class="p-0 m-0">UPDATE</h3></button>
    </div>
  </div>
  <div class="col-8 d-flex justify-content-center align-items-center flex-column">
    <!-- x button -->
    <div class="position-absolute" style="top:10px; right: 20px;">
      <a href="#" onclick="history.back(); return false;"><i class="bi bi-x-circle fs-2"></i></a>
    </div>
    <div class="container bg-white w-75 py-3 px-5 rounded">
      <div class="row m-0 p-0">
        <!-- gender field -->
        <div class="col-5 bg-success text-white rounded-5 p-2 text-center my-2">
          <h4>Gender</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <select class="form-control border-success" id="post-gender" name="gender">
            <option value="Male" <?= ($post["gender"] === "Male" ? "selected" : ""); ?>>Male</option>
            <option value="Female" <?= ($post["gender"] === "Female" ? "selected" : ""); ?>>Female</option>
            <option value="None" <?= ($post["gender"] === "None" ? "selected" : ""); ?>>None</option>
          </select>
        </div>
        <!-- age field -->
        <div class="col-5 bg-success text-white rounded-5 p-2 text-center my-2">
          <h4>Age</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <div class="d-flex justify-content-center align-items-center gap-2">
            <input type="number" class="form-control border-success" id="post-age" name="age" value="<?= $post["age"]; ?>"/>
            <select class="form-control border-success" id="post-age-unit" name="age_unit">
              <option value="Month(s)" <?= ($post["age_unit"] === "Month(s)" ? "selected" : ""); ?>>Month(s)</option>
              <option value="Year(s)" <?= ($post["age_unit"] === "Year(s)" ? "selected" : ""); ?>>Year(s)</option>
            </select>
          </div>
        </div>
        <!-- price field -->
        <div class="col-5 bg-success text-white rounded-5 p-2 text-center my-2">
          <h4>Price</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <input type="text" class="form-control border-success" id="post-price" name="price" value="<?= $post["price"]; ?>"/>
        </div>
      </div>
    </div>
    <div class="container bg-success w-75 py-3 px-5 rounded my-4">
      <div class="text-white row m-0 p-0">
        <!-- breed field -->
        <div class="col-5 text-white p-2 my-2">
          <h4>Breed:</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <input type="text" class="form-control" id="post-breed" name="breed" value="<?= $post["breed"]; ?>"/>
        </div>
        <!-- personality field -->
        <div class="col-5 text-white p-2 my-2">
          <h4>Personality:</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <input type="text" class="form-control" id="post-groom" name="groom" value="<?= $post["groom"]; ?>"/>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
<?php require "parts/footer.php"; ?>