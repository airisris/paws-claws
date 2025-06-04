<div class="p-0 d-flex justify-content-start align-items-center gap-4">
  <!-- start post -->
  <?php foreach ($posts as $post) : ?>
  <a href="/post?id=<?= $post["id"]; ?>" class="text-decoration-none text-black" style="width: 17rem;">
    <div class="card p-0 m-0 overflow-hidden post-card" style="width: 17rem;">
      <div style="max-height:300px;" class="overflow-hidden d-flex justify-content-center align-items-center">
        <img src="<?= $post["image"]; ?>" class="card-img-top" alt="...">
      </div>
      <div class="card-body position-relative">
        <h5 class="card-title"><?= $post["name"]; ?></h5>
        <p class="card-text">RM <?= $post["price"]; ?></p>
      </div>
    </div>
  </a>
  <?php endforeach; ?>
  <!-- end post -->
</div>