<?php
  // connect to database
  $database = connectToDB();

  // get data
  $post_id = $_GET["id"];

  $sql = "SELECT posts.*, users.email AS owner_email, orders.id AS order_id, orders.user_id AS order_user_id, orders.status AS status
    FROM posts JOIN users ON posts.user_id = users.id
    LEFT JOIN orders ON orders.post_id = posts.id 
    WHERE posts.id = :post_id";

  $query = $database->prepare( $sql );
  $query->execute([
    "post_id" => $post_id
  ]);
  $post = $query->fetch();
?>
<?php require "parts/header.php"; ?>
<div class="row m-0 p-0" style="height:100vh;">
  <div class="col-4 d-flex justify-content-center align-items-end flex-column">
    <div class="d-flex justify-content-center align-items-center flex-column">
      <!-- message -->
      <?php require "parts/message_success.php"; ?>
      <?php require "parts/message_error.php"; ?>
      <div class="card overflow-hidden d-flex justify-content-center align-items-center" style="width: 18rem; min-height: 370px; max-height: 370px;">
        <img width="100%" src="<?= $post["image"]; ?>"/>
      </div>
      <!-- start status button -->
      <?php if (!empty($post["order_id"])) { ?>
        <?php if(isUserLoggedIn() && $_SESSION["user"]["id"] === $post["order_user_id"] && $post["status"] === "pending") : ?>
          <div class="bg-secondary rounded-5 p-3 text-center mt-4"><a href="/post/adopt?id=<?= $post['id']; ?>" class="text-decoration-none text-white"><h3 class="m-0 p-0">ADOPT
        <?php elseif(isUserLoggedIn() && $_SESSION["user"]["id"] === $post["order_user_id"] && $post["status"] === "shipped") : ?>
          <div class="bg-success rounded-5 p-3 text-center mt-4"><a href="/post/adopt?id=<?= $post['id']; ?>" class="text-decoration-none text-white"><h3 class="m-0 p-0">RECEIVE
        <?php elseif(isUserLoggedIn() && $_SESSION["user"]["id"] === $post["order_user_id"] && $post["status"] === "received") : ?>
          <div class="bg-secondary rounded-5 p-3 text-center mt-4"><a href="/post/adopt?id=<?= $post['id']; ?>" class="text-decoration-none text-white"><h3 class="m-0 p-0">RECEIVE
        <?php else : ?>
          <div class="bg-secondary rounded-5 p-3 text-center mt-4 opacity-50" style="pointer-events:none;"><a href="/post/adopt?id=<?= $post['id']; ?>" class="text-decoration-none text-white"><h3 class="m-0 p-0">ADOPT
        <?php endif ; ?>
        </h3></a></div>
      <?php } else { ?>
        <?php if(isUserLoggedIn() && $_SESSION["user"]["id"] === $post["user_id"]) : ?>
          <div class="bg-success rounded-5 p-3 text-center mt-4 opacity-50" style="pointer-events:none;"><a href="/post/adopt?id=<?= $post['id']; ?>" class="text-decoration-none text-white"><h3 class="m-0 p-0">ADOPT</h3></a></div>
        <?php elseif(isUserLoggedIn()) : ?>
          <!-- adopt button -->
          <button type="button" data-bs-toggle="modal" data-bs-target="#postAdoptModal-<?= $post['id']; ?>" class="btn btn-success rounded-5 p-3 text-center mt-4" ><h3 class="m-0 p-0">ADOPT</h3></button>
          <!-- modal -->
          <div class="modal fade" id="postAdoptModal-<?= $post['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Adoption Form: <?= $post["name"]; ?></h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="/post/adopt">
                <div class="modal-body text-start">
                  <div class="mb-3">
                    <div class="row">
                      <!-- name field -->
                      <div class="col">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $_SESSION["user"]["name"]; ?>"/>
                      </div>
                      <!-- email field -->
                      <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?=$_SESSION["user"]["email"]; ?>" disabled/>
                      </div>
                    </div>
                  </div>
                  <!-- address field -->
                  <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address"/>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <input type="hidden" name="id" value="<?= $post["id"]; ?>">
                  <!-- adopt button -->
                  <button type="submit" class="btn btn-success">Adopt</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        <?php else : ?>
          <div class="bg-success rounded-5 p-3 text-center mt-4 opacity-50" style="pointer-events:none;"><a href="/post/adopt?id=<?= $post['id']; ?>" class="text-decoration-none text-white"><h3 class="m-0 p-0">ADOPT</h3></a></div>
        <?php endif ; ?>
      <?php } ?>
      <!-- end status button -->
    </div>
  </div>
  <div class="col-8 d-flex justify-content-center align-items-center flex-column">
    <div class="position-absolute" style="top:10px; right: 20px;">
      <a href="#" onclick="history.back(); return false;"><i class="bi bi-arrow-left-circle fs-2"></i></a>
    </div>
    <h1><?= $post["name"]; ?></h1>
    <div class="container bg-white w-75 mt-2 py-3 px-5 rounded">
      <div class="row m-0 p-0">
        <div class="col-5 bg-success text-white rounded-5 p-2 text-center my-2">
          <h4>Gender</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <h4><?= $post["gender"]; ?></h4>
        </div>
        <div class="col-5 bg-success text-white rounded-5 p-2 text-center my-2">
          <h4>Age</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <h4><?= $post["age"]; ?> <?= $post["age_unit"]; ?></h4>
        </div>
        <div class="col-5 bg-success text-white rounded-5 p-2 text-center my-2">
          <h4>Price</h4>
        </div>
        <div class="col-7 p-2 px-4 my-2">
          <h4>RM <?= $post["price"]; ?></h4>
        </div>
      </div>
    </div>
    <div class="container bg-success w-75 py-3 px-5 rounded my-4">
      <div class="text-white">
        <h4>Breed: <?= $post["breed"]; ?></h4>
        <h4>Personality: <?= $post["groom"]; ?></h4>
      </div>
    </div>
    <h4>Owner contact: <?= $post["owner_email"]; ?></h4>
  </div>
</div>
<?php require "parts/footer.php"; ?>