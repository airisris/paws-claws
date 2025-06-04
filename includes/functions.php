<?php
    // connect to database
    function connectToDB(){
        // 1. database info
        $host = "127.0.0.1";
        $database_name ="paws_claws"; // connecting to which database
        $database_user = "root";
        $database_password ="";
    
        // 2. connect PHP with the MySQL database
        // PDO (PHP Database Object)
        $database = new PDO(
            "mysql:host=$host;dbname=$database_name", // host and db name
            $database_user, // username
            $database_password // password
        );

    return $database;
    }

    function getUserByEmail($email){
        // connect to database
        $database = connectToDB();

        // get the user data by email
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $database->prepare($sql);
        $query->execute([
            "email" => $email
        ]);
        $user = $query->fetch(); // return the first row of the list

        return $user;
    }

    function getFavouriteById($post_id){
        // connect to database
        $database = connectToDB();

        // get data
        $user_id = $_SESSION["user"]["id"];

        $sql = "SELECT * FROM favourites WHERE post_id = :post_id AND user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute([
            "post_id" => $post_id,
            "user_id" => $user_id
        ]);
        $favourite = $query->fetch();

        return $favourite;
    }

    function getAdoptById($post_id){
        // connect to database
        $database = connectToDB();

        // get data
        $user_id = $_SESSION["user"]["id"];

        $sql = "SELECT * FROM orders WHERE post_id = :post_id AND user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute([
            "post_id" => $post_id,
            "user_id" => $user_id
        ]);
        $adopt = $query->fetch();

        return $adopt;
    }

    function getShipById($post_id){
        // connect to database
        $database = connectToDB();

        // get data
        $user_id = $_GET["user_id"];

        $sql = "SELECT * FROM orders WHERE post_id = :post_id AND user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute([
            "post_id" => $post_id,
            "user_id" => $user_id
        ]);
        $ship = $query->fetch();

        return $ship;
    }

    /* 
        check if user is logged in
        if user is logged in, return true
        if user is not logged in, return false
    */
    function isUserLoggedIn(){
        return isset($_SESSION["user"]);
    }

    // check if current user is an admin
    function isAdmin(){
        // check if user session is set or not
        if (isset($_SESSION["user"])) {
            // check if user is an admin
            if ($_SESSION["user"]["role"] === 'admin') {
                return true;
            }
        }
            return false;
    }

    // check if current user is an editor or admin
    function isEditor(){
        return isset($_SESSION["user"]) && ($_SESSION["user"]["role"] === 'admin' || $_SESSION["user"]["role"] === 'editor') ? true : false;
    }
?>