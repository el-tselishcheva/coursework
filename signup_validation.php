<?php
    include "db.php";

    if (array_key_exists("sign_up", $_POST)) {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $gender_id = $_POST["gender_id"];
        $birthdate = $_POST["birthdate"];
        $login = $_POST["login"];
        $password = $_POST["password"];
        // $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        insertNewUser($name, $surname, $gender_id, $birthdate, $login, $password);

        session_start();
        $_SESSION["login"] = $login;
        $user = selectUserByLogin($login);

        header("Location: /profile.php?user_id=" .$user['id']);
    }
?>