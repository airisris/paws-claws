<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = $_POST["user_id"];

  // delete from orders table
  $sql = "DELETE FROM orders WHERE user_id = :id";
  $query = $database->prepare($sql);
  $query->execute([
    "id" => $user_id
  ]);
      
  // delete from favourites table
  $sql = "DELETE FROM favourites WHERE user_id = :id";
  $query = $database->prepare($sql);
  $query->execute([
    "id" => $user_id
  ]);

  // delete from posts table
  $sql = "DELETE FROM posts WHERE user_id = :id";
  $query = $database->prepare( $sql );
  $query->execute([
    "id" => $user_id
  ]);
      
  // then only can delete from posts table
  $sql = "DELETE FROM users WHERE id = :id";
  $query = $database->prepare( $sql );
  $query->execute([
    "id" => $user_id
  ]);

  // redirect
  $_SESSION["success"] = "User deleted successfully.";
  header("Location: /users"); 
  exit;
?>