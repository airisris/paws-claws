<?php
    // connect to databse
    $database = connectToDB();

    // get data
    $id = $_POST["id"];
    $name = $_POST["name"];
    $breed = $_POST["breed"];
    $gender = $_POST["gender"];
    $age = $_POST["age"];
    $age_unit = $_POST['age_unit'];
    $price = $_POST["price"];
    $groom = $_POST["groom"];
    $image = $_FILES["image"];

    // error checking
    if (empty($name)||empty($breed)||empty($age)||empty($price)||empty($groom)||empty($image)){
      $_SESSION["error"] = "All fields are required.";
      header("Location: /post-edit?id=". $id);
      exit;
    } 

    if (!empty($image["name"])){
      $target_folder = "uploads/";
      $target_path = $target_folder . date("YmdHisv") . "_" . basename( $image["name"] );
      move_uploaded_file( $image["tmp_name"] , $target_path );

      // edit post with image path
      $sql = "UPDATE posts SET name = :name, image = :image, gender = :gender, age = :age, age_unit = :age_unit, price = :price, breed = :breed, groom = :groom WHERE id = :id";
      $query = $database->prepare($sql);
      $query->execute([
          "id" => $id,
          "name" => $name,
          "image" => $target_path,
          "gender" => $gender,
          "age" => $age,
          "age_unit" => $age_unit,
          "price" => $price,
          "breed" => $breed,
          "groom" => $groom
      ]);
  } else {

      // edit post without image path
      $sql = "UPDATE posts SET name = :name, gender = :gender, age = :age, age_unit = :age_unit, price = :price, breed = :breed, groom = :groom WHERE id = :id";
      $query = $database->prepare($sql);
      $query->execute([
          "id" => $id,
          "name" => $name,
          "gender" => $gender,
          "age" => $age,
          "age_unit" => $age_unit,
          "price" => $price,
          "breed" => $breed,
          "groom" => $groom
      ]);
  }
    
    // redirect
    $_SESSION["success"] = "Post edited successfully.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
?>