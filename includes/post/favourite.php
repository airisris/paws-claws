<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = $_SESSION["user"]["id"];
  $post_id = $_GET["id"];

  // check if user already favourited or not
  $favourite = getFavouriteById($post_id);

  if ($favourite){
    $sql = "DELETE FROM favourites WHERE user_id = :user_id AND post_id = :post_id";
    $query = $database->prepare( $sql );
    $query->execute([
      "user_id" => $user_id,
      "post_id" => $post_id
    ]);
    // redirect
    $_SESSION["error"] = "Post removed successfully from favourite.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
  } else {
    $sql = "INSERT INTO favourites (`user_id`, `post_id`) VALUES (:user_id, :post_id)";
    $query = $database->prepare( $sql );
    $query->execute([
      "user_id" => $user_id,
      "post_id" => $post_id
    ]);
    // redirect
    $_SESSION["success"] = "Post added successfully to favourite.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
  }
?>