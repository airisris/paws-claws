<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = $_GET["user_id"];
  $post_id = $_GET["post_id"];

  // check if post already ship or not
  $ship = getShipById($post_id);

  if ($ship && $ship["status"] === "pending"){
    $sql = "UPDATE orders SET status = 'shipped' WHERE user_id = :user_id AND post_id = :post_id";
    $query = $database->prepare( $sql );
    $query->execute([
      "user_id" => $user_id,
      "post_id" => $post_id
    ]);
    // message
    $_SESSION["success"] = "Pet successfully shipped.";
    
  } else {
    $sql = "UPDATE orders SET status = 'pending' WHERE user_id = :user_id AND post_id = :post_id";
    $query = $database->prepare( $sql );
    $query->execute([
      "user_id" => $user_id,
      "post_id" => $post_id
    ]);
    // message
    $_SESSION["success"] = "Pet successfully unshipped.";

  }
  // redirect
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit;
?>