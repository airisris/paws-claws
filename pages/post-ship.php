<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null;
  $search_keyword = isset($_GET["search"]) ? $_GET["search"] : "";

  $sql = "SELECT posts.*, orders.name AS order_user_name, users.email AS order_user_email, orders.id AS order_id, orders.status AS order_status, orders.user_id AS order_user_id, orders.address
    FROM posts 
    /* select the user who ordered the post */
    JOIN orders ON posts.id = orders.post_id
    JOIN users ON orders.user_id = users.id AND posts.user_id = :user_id
    WHERE ( users.name LIKE :keyword OR posts.name LIKE :keyword )
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
    <a href="/post-ship"><button class="px-4 py-1 position-absolute rounded-top bg-white border-0" style="top:-32px; left: 125px;">To Ship</button></a>
    <a href="/post-history"><button class="px-4 py-1 position-absolute rounded-top bg-secondary border-0" style="top:-32px; left: 235px;">History</button></a>
    <!-- message -->
    <?php require "parts/message_success.php"; ?>
    <?php require "parts/message_error.php"; ?>
    <!-- start table -->
    <table class="table">
      <?php require "parts/message_success.php"; ?>
      <?php if(!empty($posts)) { ?>
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Post</th>
          <th scope="col">Address</th>
          <th scope="col" class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $post) : ?>
        <?php if ($post["order_status"] !== "received") : ?>
        <tr>
          <th scope="row"><?= $post["id"]; ?></th>
          <td><?= $post["order_user_name"]; ?></td>
          <td><?= $post["order_user_email"]; ?></td>
          <td><?= $post["name"]; ?></td>
          <td><?= $post["address"]; ?></td>
          <td class="text-end">
          <div class="d-flex justify-content-end align-items-center gap-4">
            <a href="/post?id=<?= $post["id"]; ?>" class="text-decoration-none text-black"><button class="btn btn-sm btn-primary">View More</button></a>
            <!-- ship button -->
            <form method="GET" action="/post/ship">
              <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
              <input type="hidden" name="user_id" value="<?= $post['order_user_id']; ?>">
              <?php if ($post["order_status"] === "pending") { ?>
                <button type="submit" class="btn btn-success btn-sm">
              <?php } else { ?>
                <button type="submit" class="btn btn-secondary btn-sm">
              <?php } ?>
                Shipped</button>
            </form>
            <!-- delete button -->
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#userDeleteModal-<?= $post['order_id']; ?>">
              <i class="bi bi-trash"></i>
            </button>
            <!-- delete modal -->
            <div class="modal fade" id="userDeleteModal-<?= $post['order_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this adoption?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-start">
                    <p>You're currently trying to delete this adoption: <?= $post['name']; ?></p>
                    <p>This action cannot be reversed.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" action="/post/adopt-delete">
                      <input type="hidden" name="order_id" value="<?= $post["order_id"]; ?>"/>
                      <button class="btn btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </td>
        </tr>
        <?php endif ?>
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