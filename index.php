<?php
    session_start();
    require "includes/functions.php";
    require "includes/class_auth.php";

    // init class
    $auth = new Auth();

    $path = $_SERVER["REQUEST_URI"];

    //remove all the query string from the url
    $path = parse_url($path, PHP_URL_PATH);

    // once you figure outh the path, then we need to load relevant content based on the path
    switch ($path){
        case '/login':
            require "pages/login.php";
            break;
        case '/signup':
            require "pages/signup.php";
            break;
        case '/logout':
            require "pages/logout.php";
            break;
        case '/post':
            require "pages/post.php";
            break;
        case '/post-add':
            require "pages/post-add.php";
            break;
        case '/post-edit':
            require "pages/post-edit.php";
            break;
        case '/mypost':
            require "pages/mypost.php";
            break;
        case '/users':
            require "pages/users.php";
            break;
        case '/favourite':
            require "pages/post-favourite.php";
            break;
        case '/post-adopt':
            require "pages/post-adopt.php";
            break;
        case '/post-ship':
            require "pages/post-ship.php";
            break;
        case '/post-history':
            require "pages/post-history.php";
            break;
        //actions routes
        case '/auth/login':
            $auth->login();
            break;
        case '/auth/signup':
            $auth->signup();
            break;
        case '/post/add':
            require "includes/post/add.php";
            break;
        case '/post/edit':
            require "includes/post/edit.php";
            break;
        case '/post/delete':
            require "includes/post/delete.php";
            break;
        case '/post/favourite':
            require "includes/post/favourite.php";
            break;
        case '/post/adopt':
            require "includes/post/adopt.php";
            break;
        case '/post/adopt-delete':
            require "includes/post/adopt-delete.php";
            break;
        case '/post/ship':
            require "includes/post/ship.php";
            break;
        case '/user/delete':
            require "includes/user/delete.php";
            break;
        case '/user/edit':
            require "includes/user/edit.php";
            break;
        case '/user/changepwd':
            require "includes/user/changepwd.php";
            break;

        default:
            require "pages/home.php";
            break;
    }
?>