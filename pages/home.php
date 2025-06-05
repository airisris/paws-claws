<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null;
  $search_keyword = isset($_GET["search"]) ? $_GET["search"] : "";
  $price1 = isset($_GET["price1"]) ? $_GET["price1"] : "";
  $price2 = isset($_GET["price2"]) ? $_GET["price2"] : "";
  $age1 = isset($_GET["age1"]) ? $_GET["age1"] : "";
  $age2 = isset($_GET["age2"]) ? $_GET["age2"] : "";
  $category_id = isset($_GET["category_id"]) ? $_GET["category_id"] : "";

  $sql = "SELECT posts.*, favourites.favourite, categories.name AS category_name 
    FROM posts 
    LEFT JOIN favourites ON posts.id = favourites.post_id AND favourites.user_id = :user_id 
    LEFT JOIN categories ON posts.category_id = categories.id";

  $filter = [];
  $data = ["user_id" => $user_id];

  // search filter
  if(!empty($search_keyword)){
    $filter[] = "( categories.name LIKE :keyword OR posts.breed LIKE :keyword OR posts.name LIKE :keyword )";
    $data["keyword"] = "%$search_keyword%";
  }

  // price filter
  if(!empty($price1) && !empty($price2)){
    $filter[] = "posts.price BETWEEN :price1 AND :price2";
    $data["price1"] = $price1;
    $data["price2"] = $price2;
  }

  // age filter
  if(!empty($age1) && !empty($age2)){
    $filter[] = "posts.age BETWEEN :age1 AND :age2";
    $data["age1"] = $age1;
    $data["age2"] = $age2;
  } 

  // category filter
  if(!empty($category_id)){
    $filter[] = "posts.category_id = :category_id";
    $data["category_id"] = $category_id;
  } 
  
  if (!empty($filter)){
    $sql .= " WHERE " . implode(" AND ", $filter);
  }
 
  $sql .= " ORDER BY posts.id DESC";
  $query = $database->prepare($sql);
  $query->execute($data);
  $posts = $query->fetchAll();
?>
<?php require "parts/header.php"; ?>
<?php require "parts/layout.php"; ?>
<div class="col-9 p-0 m-0">
  <div class="p-3 ps-4 bg-white rounded me-4 position-relative" style="margin-top:100px;">
    <!-- tags -->
    <a href="/"><button class="px-4 py-1 position-absolute rounded-top bg-white border-0" style="top:-32px;">All</button></a>
    <a href="/mypost"><button class="px-4 py-1 position-absolute rounded-top bg-secondary border-0" style="top:-32px; left:100px;">My Post</button></a>
    <!-- messages -->
    <?php require "parts/message_success.php"; ?>
    <?php require "parts/message_error.php"; ?>
    <div class="row d-flex justify-content-start align-content-around p-0 m-0">
      <?php if(!empty($posts)) { ?>
      <!-- start post -->
      <?php foreach ($posts as $post) : ?>
      <div class="col-4 p-0 mx-0 my-3">
        <div class="card p-0 m-0 overflow-hidden post-card" style="width: 17rem;">
          <div style="min-height: 300px; max-height: 300px;" class="overflow-hidden d-flex justify-content-center align-items-center">
            <img src="<?= $post["image"]; ?>" class="card-img-top w-100" alt="...">
          </div>
          <div class="card-body position-relative">
            <h5 class="card-title"><?= $post["name"]; ?></h5>
            <p class="card-text">RM <?= $post["price"]; ?></p>
            <a href="/post?id=<?= $post["id"]; ?>" class="text-decoration-none text-black"><button class="rounded p-2" style="width: 15rem;">View More</button></a>
            <!-- editor/admin can edit all posts -->
            <?php if (isEditor()) : ?>
            <div class="position-absolute d-flex justify-content-end align-items-center gap-2" style="top: -40px; right: 50px;">
              <!-- delete button -->
              <button type="button" class="btn btn-danger btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#postDeleteModal-<?= $post["id"]; ?>">
                <i class="bi bi-trash"></i>
              </button>
              <!-- modal -->
              <div class="modal fade" id="postDeleteModal-<?= $post['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this post?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                      <p>You're currently trying to delete this post: <?= $post['name']; ?></p>
                      <p>This action cannot be reversed.</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <form method="POST" action="/post/delete">
                        <input type="hidden" name="post_id" value="<?= $post["id"]; ?>"/>
                        <button class="btn btn-danger"><i class="bi bi-trash"></i></button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- edit button -->
              <a href="/post-edit?id=<?= $post['id']; ?>" class="btn btn-secondary btn-sm rounded-circle">
                <i class="bi bi-pencil"></i>
              </a>
            </div>
            <?php endif ?>
            <!-- favourite icon -->
            <form method="GET" action="">
              <div class="position-absolute" style="bottom: 150px; right: 15px;">
                <?php if(isUserLoggedIn()) { ?>
                  <a href="/post/favourite?id=<?= $post['id']; ?>">
                  <?php if ($post["favourite"] == 1) : ?>
                    <i class="bi bi-suit-heart-fill fs-3 text-danger"></i></a>
                  <?php else : ?>
                    <i class="bi bi-suit-heart-fill fs-3 text-secondary"></i></a>
                  <?php endif ; ?>
                <?php } ?>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <!-- end post -->
      <?php } else { ?>
        <img width="100%" src="https://preview.redd.it/me-after-half-the-grad-students-presentations-i-sit-through-v0-0uusary3py6d1.jpeg?width=640&crop=smart&auto=webp&s=8cbb93d0fba75d6a231a18d36871c4cf58d178cc">
      <?php } ?>
    </div>
  </div>
</div>
<?php require "parts/footer.php"; ?>