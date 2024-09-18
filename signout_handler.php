<?php
    include "db.php";
    
    if (array_key_exists("sign_out", $_POST)) {
        $url = $_POST["sign_out"];
        session_start();

        if ($url == "/profile_edit.php") {
            $login = $_SESSION["login"];
            $user = selectUserByLogin($login);
            $url = "/profile.php?user_id=" .$user['id'];
        } else if ($url == "/admin_panel.php") {
            $url = "/";
        }

        session_destroy();
        header("Location: " .$url);
    }
?>