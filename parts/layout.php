<?php
  // connect to database
  $database = connectToDB();

  $sql = "SELECT * FROM categories";

  $query = $database->prepare( $sql );
  $query->execute();
  $categories = $query->fetchAll();
?>
<header id="header position-fixed p-0 m-0 w-100 bg-secondary" style="z-index: 10; position: fixed; width:100vw">
<div class="row p-2 m-0 w-100" style="backdrop-filter: blur(8px);">
  <div class="col-3 d-flex justify-content-around align-items-center">
    <a href="/" style="width: 60px;"><img class="p-0 m-0" width="100%" src="https://logosandtypes.com/wp-content/uploads/2021/04/puppy-up.svg"/></a>
    <?php if(isUserLoggedIn()) : ?>
      <p class="p-0 m-0"><?php echo ( isUserLoggedIn() ? "Hello, " . $_SESSION["user"]["name"] : ""); ?></p>
      <a href="/logout">Logout</a>
    <?php else : ?>
      <a href="/login">Login</a>
      <a href="/signup">Signup</a>
    <?php endif ; ?>
  </div>
  <div class="col-9 d-flex justify-content-center align-items-center">
    <form method="GET" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" class="p-0 m-0 w-75 d-flex justify-content-center align-items-center gap-2">
      <input type="text" name="search" class="form-control" placeholder="Type a keyword to search..." value="<?= htmlspecialchars($search_keyword) ?>"/>
      <button class="p-0 m-0 border-0" style="background-color: rgba(255, 255, 255, 0);"><i class="bi bi-search fs-5"></i></button>
    </form>
  </div>
</div>
</header>
<div class="row p-2 m-0 w-100">
  <div class="col-3 d-flex justify-content-center">
    <!-- start sidebar -->
    <div class="card position-sticky p-3 pt-3 m-0" style="top:80px; height:530px; width:250px;">
      <form method="GET" action="/">
        <!-- price filter -->
        <div class="d-flex justify-content-between align-items-center pb-3">
          <h4 class="p-0 m-0">Price :</h4>
          <a href="/" class="p-0 m-0">Clear</a>
        </div>
        <div class="d-flex justify-content-between align-content-center gap-3 pb-3">
          <input style="width:50%;" type="number" name="price1" id="price1" value="<?= $price1 ?>" class="form-control"/>
          <p class="p-0 m-0">-</p>
          <input style="width:50%;" type="number" name="price2" id="price2" value="<?= $price2 ?>" class="form-control"/>
        </div>
        <div class="d-flex justify-content-center align-items-center pb-3">
          <button class="w-100 rounded-5" type="submit" name="filter-price" value="price">Filter</button>
        </div>
        <!-- age filter -->
        <div class="d-flex justify-content-between align-items-center pb-3">
          <h4 class="p-0 m-0">Age :</h4>
          <a href="/" class="p-0 m-0">Clear</a>
        </div>
        <div class="d-flex justify-content-between align-content-center gap-3 pb-3">
          <input style="width:50%;" type="number" name="age1" id="age1" value="<?= $age1 ?>" class="form-control"/>
          <p class="p-0 m-0">-</p>
          <input style="width:50%;" type="number" name="age2" id="age2" value="<?= $age2 ?>" class="form-control"/>
        </div>
        <div class="d-flex justify-content-center align-items-center pb-3">
          <button class="w-100 rounded-5" type="submit" name="filter-age" value="age">Filter</button>
        </div>
        <!-- category filter -->
        <div class="d-flex justify-content-between align-items-center pb-3">
          <h4 class="p-0 m-0">Categories :</h4>
          <a href="/" class="p-0 m-0">Clear</a>
        </div>
        <div class="row d-flex justify-content-around align-content-around pb-2">
          <form method="GET" action="">
            <?php foreach ($categories as $category) : ?>
            <div class="col-6 gap-1 my-1">
              <button class="w-100 rounded-5" type="submit" name="category_id" value="<?= $category["id"]; ?>"><?= $category["name"]; ?></button>
            </div>
            <?php endforeach; ?>
          </form>
        </div>
      </form>
      <!-- icons -->
      <div class="d-flex justify-content-end align-items-center gap-2">
        <?php if (isAdmin()) : ?>
          <a href="/users"><i class="bi bi-person-fill fs-2"></i></a>
        <?php endif ; ?>
        <a href="/post-adopt"><i class="bi bi-cart-fill fs-3"></i></a>
        <a href="/favourite"><i class="bi bi-suit-heart-fill fs-3"></i></a>
        <a href="/post-add"><i class="bi bi-plus-circle-fill fs-3"></i></a>
      </div>
    </div>
    <!-- end sidebar -->
  </div>