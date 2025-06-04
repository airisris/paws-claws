<?php
  // connect to database
  $database = connectToDB();

  // get data
  $user_id = $_SESSION["user"]["id"];
  // to adopt, use form = POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST["id"];
    $address = $_POST["address"];
    $name = $_POST["name"];
    $timestamp = date("Y-m-d H:i:s");

    // error checking
    if (empty($address) || empty($name)) {
        $_SESSION["error"] = "All fields are required.";
        header("Location: /post?id=$post_id");
        exit;
    }
  // to unadopt/change status, get id from link = GET
  } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_id = $_GET["id"];
  }

  // check if user already adopt or not
  $adopt = getAdoptById($post_id);

  if ($adopt){
    if ($adopt["status"] === "pending"){
      $sql = "DELETE FROM orders WHERE user_id = :user_id AND post_id = :post_id";
      $query = $database->prepare( $sql );
      $query->execute([
        "user_id" => $user_id,
        "post_id" => $post_id
      ]);
      // redirect
      $_SESSION["success"] = "Post removed successfully from adopt.";
      header("Location: " . $_SERVER['HTTP_REFERER']);
      exit;
    }
    elseif ($adopt["status"] === "shipped"){
      $sql = "UPDATE orders SET status = 'received' WHERE user_id = :user_id AND post_id = :post_id";
      $query = $database->prepare( $sql );
      $query->execute([
        "user_id" => $user_id,
        "post_id" => $post_id
      ]);
      // redirect
      $_SESSION["success"] = "Pet successfully received.";
      header("Location: " . $_SERVER['HTTP_REFERER']);
      exit;
    }
    elseif ($adopt["status"] === "received"){
      $sql = "UPDATE orders SET status = 'shipped' WHERE user_id = :user_id AND post_id = :post_id";
      $query = $database->prepare( $sql );
      $query->execute([
        "user_id" => $user_id,
        "post_id" => $post_id
      ]);
      // redirect
      $_SESSION["success"] = "Pet successfully unreceived.";
      header("Location: " . $_SERVER['HTTP_REFERER']);
      exit;
    }
  } else {
    // insert order/adopt
    $sql = "INSERT INTO orders (`user_id`, `post_id`, `address`, `name`, `timestamp`) VALUES (:user_id, :post_id, :address, :name, :timestamp)";
    $query = $database->prepare( $sql );
    $query->execute([
      "user_id" => $user_id,
      "post_id" => $post_id,
      "address" => $address,
      "name" => $name,
      "timestamp" => $timestamp
    ]);
    // redirect
    $_SESSION["success"] = "Post added successfully to adopt.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
  }


?>