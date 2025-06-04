<?php
  // connect to database
  $database = connectToDB();

  // get data
  $order_id = $_POST["order_id"];

  // delete adoption
  $sql = "DELETE FROM orders WHERE id = :order_id";
  $query = $database->prepare($sql);
  $query->execute([
    "order_id" => $order_id
  ]);

  // redirect
  $_SESSION["success"] = "Adoption deleted successfully.";
  header("Location: " . $_SERVER['HTTP_REFERER']); 
  exit;
?>