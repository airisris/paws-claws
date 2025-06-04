<?php
  // connect to database
  $database = connectToDB();

  // get data
  $id = $_POST["id"];
  $password = $_POST["password"]; 
  $confirm_password = $_POST["confirm_password"]; 

  // error checking
  if (empty($password)||empty($confirm_password)){
    $_SESSION["error"] = "All fields are required";
  } else if ($password !== $confirm_password){
    $_SESSION["error"] = "Password is not matched.";
  } else {
    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
      "id" => $id,
      "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);
    $_SESSION["success"] = "Password changed successfully.";
  }
  // redirect
  header("Location: /users");
  exit;
?>