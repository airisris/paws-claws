<?php
    // remove the user session
    unset($_SESSION["user"]);

    // redirect
    header("Location: /");
    exit;
?>