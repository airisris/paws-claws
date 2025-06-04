<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null;
  $search_keyword = isset($_GET["search"]) ? $_GET["search"] : "";

  $sql = "SELECT posts.*, posts.id AS post_id, orders.* FROM orders JOIN posts ON posts.id = orders.post_id AND orders.user_id = :user_id
    WHERE ( orders.status LIKE :keyword OR orders.timestamp LIKE :keyword OR posts.name LIKE :keyword )
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
  <div class="p-3 ps-4 bg-white rounded me-4 position-relative" style="margin-top:100px; min-height: 510px;">
    <!-- tags -->
    <a href="/post-adopt"><button class="px-4 py-1 position-absolute rounded-top bg-secondary border-0" style="top:-32px;">Adopt</button></a>
    <a href="/post-ship"><button class="px-4 py-1 position-absolute rounded-top bg-secondary border-0" style="top:-32px; left: 125px;">To Ship</button></a>
    <a href="/post-history"><button class="px-4 py-1 position-absolute rounded-top bg-white border-0" style="top:-32px; left: 235px;">History</button></a>
    <!-- start table -->
    <table class="table">
      <?php require "parts/message_error.php"; ?>
      <?php require "parts/message_success.php"; ?>
      <?php if(!empty($posts)) { ?>
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Post</th>
          <th scope="col">Timestamp</th>
          <th scope="col">Status</th>
          <th scope="col" class="text-end">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $post) : ?>
        <tr>
          <th scope="row"><?= $post["post_id"]; ?></th>
          <td><?= $post["name"]; ?></td>
          <td><?= $post["timestamp"]; ?></td>
          <td>
            <!-- status -->
            <?php if ($post["status"] == "pending") { ?>
              <span class="badge bg-info text-white">
            <?php } else if ($post["status"] == "shipped") { ?>
              <span class="badge bg-warning text-white">
            <?php } else { ?>
              <span class="badge bg-success">
            <?php } ?>
            <?= $post["status"]; ?></span> 
          </td>
          <td class="text-end">
            <a href="/post?id=<?= $post["post_id"]; ?>" class="text-decoration-none text-black"><button class="btn btn-sm btn-primary">View More</button></a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
      <?php } else { ?>
        <img width="100%" src="https://i.imgflip.com/5dxwth.jpg?a485208">
      <?php } ?>
    </table>
    <!-- end table -->
  </div>
</div>
<?php require "parts/footer.php"; ?>