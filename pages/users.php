<?php
  // check if the user is not an admin
  if (!isAdmin()){
    header("Location: /");
    exit;
  }
  
  // connent to database
  $database = connectToDB();

  // get data
  $search_keyword = isset($_GET["search"]) ? $_GET["search"] : "";

  $sql = "SELECT * FROM users
    WHERE ( users.name LIKE :keyword OR users.role LIKE :keyword )";

  $query = $database->prepare( $sql );
  $query->execute([
    "keyword" => "%$search_keyword%"
  ]);
  $users = $query->fetchAll();
?>
<?php require "parts/header.php"; ?>
<?php require "parts/layout.php"; ?>
<div class="col-9 p-0 m-0">
  <div class="p-3 ps-4 bg-white rounded me-4 position-relative" style="margin-top:100px; min-height: 510px;">
    <!-- tag -->
    <a href="/users"><button class="px-4 py-1 position-absolute rounded-top bg-white border-0" style="top:-32px;">Users</button></a>
    <table class="table">
      <?php require "parts/message_success.php"; ?>
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Role</th>
          <th scope="col" class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- start user -->
        <?php foreach ($users as $user) { ?>
        <tr>
          <th scope="row"><?= $user["id"]; ?></th>
          <td><?= $user["name"]; ?></td>
          <td><?= $user["email"]; ?></td>
          <!-- role -->
          <td>
            <?php if ($user["role"] == "admin") { ?>
              <span class="badge bg-primary text-white">
            <?php } else if ($user["role"] == "editor") { ?>
              <span class="badge bg-info text-white">
            <?php } else { ?>
              <span class="badge bg-success">
            <?php } ?>
            <?= $user["role"]; ?></span>
          </td>
          <td class="text-end">
            <div class="buttons d-flex justify-content-around">
              <!-- edit button  -->
              <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#userEditModal-<?= $user['id']; ?>">
                <i class="bi bi-pencil"></i>
              </button>
              <!-- modal -->
              <div class="modal fade" id="userEditModal-<?= $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Edit user: <?= $user["name"]; ?></h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="/user/edit">
                      <div class="modal-body text-start">
                        <div class="mb-3">
                          <div class="row">
                            <div class="col">
                              <label for="name" class="form-label">Name</label>
                              <input type="text" class="form-control" id="name" name="name" value="<?= $user["name"]; ?>"/>
                            </div>
                            <div class="col">
                              <label for="email" class="form-label">Email</label>
                              <input type="email" class="form-control" id="email" name="email" value="<?= $user["email"]; ?>" disabled/>
                            </div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label for="role" class="form-label">Role</label>
                          <select class="form-control" id="role" name="role">
                            <option value="">Select an option</option>
                            <option value="user" <?= ($user["role"] === "user" ? "selected" : ""); ?>>User</option>
                            <option value="editor" <?= ($user["role"] === "editor" ? "selected" : ""); ?>>Editor</option>
                            <option value="admin" <?= ($user["role"] === "admin" ? "selected" : ""); ?>>Admin</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="hidden" name="id" value="<?= $user["id"]; ?>">
                        <button type="submit" class="btn btn-success">Update</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- change pwd button -->
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#userChangepwdModal-<?= $user['id']; ?>">
                <i class="bi bi-key"></i>
              </button>
              <!-- modal -->
              <div class="modal fade" id="userChangepwdModal-<?= $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Change password user: <?= $user["name"]; ?></h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="/user/changepwd">
                      <div class="modal-body text-start">
                        <div class="mb-3">
                          <div class="row">
                            <div class="col">
                              <label for="password" class="form-label">Password</label>
                              <input type="password" class="form-control" id="password" name="password"/>
                            </div>
                            <div class="col">
                              <label for="confirm-password" class="form-label">Confirm Password</label>
                              <input type="password" class="form-control" id="confirm-password" name="confirm_password"/>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="hidden" name="user_id" value="<?= $user["id"]; ?>"/>
                        <button class="btn btn-warning">Change</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- delete button -->
              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#userDeleteModal-<?= $user['id']; ?>">
                <i class="bi bi-trash"></i>
              </button>
              <!-- modal -->
              <div class="modal fade" id="userDeleteModal-<?= $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Delete user: <?= $user["name"]; ?></h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                      <p>Are you sure you want to delete this user?</p>
                      <p>This action cannot be reversed.</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <form method="POST" action="/user/delete">
                        <input type="hidden" name="user_id" value="<?= $user["id"]; ?>"/>
                        <button class="btn btn-danger">Delete</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <?php } ?>
        <!-- end user -->
      </tbody>
    </table>
  </div>
</div>
<?php require "parts/footer.php"; ?>