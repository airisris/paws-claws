<?php
    // connect to database
    $database = connectToDB();

    // get data
    $post_id = $_POST["post_id"];

    // delete from orders table
    $sql = "DELETE FROM orders WHERE post_id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        "id" => $post_id
    ]);
    
    // delete from favourites table
    $sql = "DELETE FROM favourites WHERE post_id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        "id" => $post_id
    ]);

    // then only can delete from posts table
    $sql = "DELETE FROM posts WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
        "id" => $post_id
    ]);

    // redirect
    $_SESSION["success"] = "Post deleted successfully.";
    header("Location: " . $_SERVER['HTTP_REFERER']); 
    exit;
?>