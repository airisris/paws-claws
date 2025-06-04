<?php
    // connect to database
    $database = connectToDB();

    // get data
    $id = $_POST["id"];
    $name = $_POST["name"];
    $role = $_POST["role"]; 

    // error checking
    if (empty($id)||empty($name)||empty($role)){
        $_SESSION["error"] = "All fields are required.";
    } else {
        // edit user
        $sql = "UPDATE users SET name = :name, role = :role WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute([
            "id" => $id,
            "name" => $name,
            "role" => $role
        ]);
        $_SESSION["success"] = "User edited successfully.";
    }
    // redirect
    header("Location: /users");
    exit;
?>