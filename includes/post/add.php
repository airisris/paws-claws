<?php
  // connect to database
  $database = connectToDB();

  // get all the data from the form using $_POST
  $name = $_POST["name"];
  $category_id = $_POST["category_id"];
  $breed = $_POST["breed"];
  $gender = $_POST["gender"];
  $age = $_POST["age"];
  $age_unit = $_POST['age_unit'];
  $price = $_POST["price"];
  $groom = $_POST["groom"];
  $user_id = $_SESSION["user"]["id"];
  $image = $_FILES["image"];

  // error checking
  if (empty($name)||empty($breed)||empty($age)||empty($price)||empty($groom)||$image['error'] === UPLOAD_ERR_NO_FILE){
    $_SESSION["error"] = "All fields are required.";
    header("Location: /post-add");
    exit;
  } 

  // make sure $image is not empty
  if ( !empty( $image ) ) {
    $target_folder = "uploads/";
    $target_path = $target_folder . date("YmdHisv") . "_" . basename($image["name"]);
    move_uploaded_file($image["tmp_name"] , $target_path);
  }


  // create a post
  $sql = "INSERT INTO posts (`name`, `category_id`, `image`, `gender`, `age`, `age_unit`, `price`, `breed`, `groom`, `user_id`) VALUES (:name, :category_id, :image, :gender, :age, :age_unit, :price, :breed, :groom, :user_id)";
  $query = $database->prepare($sql);
  $query->execute([
      "name" => $name,
      "category_id" => $category_id,
      "image" => isset($target_path) ? $target_path : "",
      "gender" => $gender,
      "age" => $age,
      "age_unit" => $age_unit,
      "price" => $price,
      "breed" => $breed,
      "groom" => $groom,
      "user_id" => $user_id
  ]);

  // redirect
  $_SESSION["success"] = "Post created successfully.";
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit;
?>