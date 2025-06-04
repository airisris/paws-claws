<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null;
  $search_keyword = isset($_GET["search"]) ? $_GET["search"] : "";

  $sql = "SELECT posts.*, categories.name AS category_name FROM orders JOIN posts ON posts.id = orders.post_id AND orders.user_id = :user_id
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE ( categories.name LIKE :keyword OR posts.breed LIKE :keyword OR posts.name LIKE :keyword )
    ORDER BY posts.id DESC";

  // if user not logged in, cannot access this page
  if (!isUserLoggedIn()) {
    $_SESSION["error"] = "Please log in to access this page.";
    header("Location: /");
    exit; 
  }  

  $query = $database->prepare( $sql );
  $query->execute([
    "user_id" => $user_id,
    "keyword" => "%$search_keyword%"
  ]);
  $posts = $query->fetchAll();
?>
<?php require "parts/header.php"; ?>
<?php require "parts/layout.php"; ?>
<div class="col-9 p-0 m-0">
  <div class="p-3 ps-4 bg-white rounded me-4 position-relative" style="margin-top:100px;">
    <!-- tags -->
    <a href="/post-adopt"><button class="px-4 py-1 position-absolute rounded-top bg-white border-0" style="top:-32px;">Adopt</button></a>
    <a href="/post-ship"><button class="px-4 py-1 position-absolute rounded-top bg-secondary border-0" style="top:-32px; left: 125px;">To Ship</button></a>
    <a href="/post-history"><button class="px-4 py-1 position-absolute rounded-top bg-secondary border-0" style="top:-32px; left: 235px;">History</button></a>
    <!-- message -->
    <?php require "parts/message_success.php"; ?>
    <?php require "parts/message_error.php"; ?>
    <div class="row d-flex justify-content-start align-content-around p-0 m-0">
      <?php if(!empty($posts)) { ?>
      <!-- start post -->
      <?php foreach ($posts as $post) : ?>
      <div class="col-4 p-0 mx-0 my-3">
        <div class="card p-0 m-0 overflow-hidden post-card" style="width: 17rem;">
          <div style="min-height: 300px; max-height: 300px;" class="overflow-hidden d-flex justify-content-center align-items-center">
            <img src="<?= $post["image"]; ?>" class="card-img-top" alt="...">
          </div>
          <div class="card-body position-relative">
            <h5 class="card-title"><?= $post["name"]; ?></h5>
            <p class="card-text">RM <?= $post["price"]; ?></p>
            <a href="/post?id=<?= $post["id"]; ?>" class="text-decoration-none text-black"><button class="rounded p-2" style="width: 15rem;">View More</button></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <!-- end post -->
      <?php } else { ?>
        <img width="100%" src="https://i.imgflip.com/5dxwth.jpg?a485208">
      <?php } ?>
    </div>
  </div>
</div>
<?php require "parts/footer.php"; ?>