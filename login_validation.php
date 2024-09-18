<?php
    include "db.php";

    // if (array_key_exists("log_in", $_POST)) {
        $url= !empty($_POST['log_in']) ? $_POST['log_in'] : "/";
        echo $url;
        $login = $_POST["login"];
        $password = $_POST["password"];
        // $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $attempt = loginHandler($login, $password)['login_handler'];
        if (checkLogin($login)['check_login']) {
            $is_banned = selectUserByLogin($login)['is_banned'];
        } else {
            $is_banned = false;
        }

        if ($attempt && !$is_banned) {
            session_start();
            $_SESSION["login"] = $login;
            echo 'Успешно!';
            header("Location: " .$url);
        } else if (!$is_banned && !$attempt) {
            echo 'Неверный логин или пароль.';
        } else if ($is_banned) {
            echo 'Отказано в доступе.';
        }
    // }
?>