<?php
    include "db.php";

    session_start();
    $login_old =$_SESSION["login"];

    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $gender_id = $_POST["gender_id"];
    $birthdate = $_POST["birthdate"];

    $login_new = !empty($_POST['login']) ? $_POST['login'] : null;
    $password_old = !empty($_POST['password_old']) ? $_POST['password_old'] : null;

    $password_new = null;
    if ($password_old != '') {
        $password_new = !empty($_POST['password_new']) ? $_POST['password_new'] : null;
    }

    $img_name = null;
    if ($_FILES['img']['size'] != 0) {
        $img_name = time() ."_". $_FILES['img']['name'];
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_path = $_SERVER['DOCUMENT_ROOT'] .'/img/'. $img_name;
        $img_upload = move_uploaded_file($img_tmp_name, $img_path);
    }

    updateUser($name, $surname, $gender_id, $birthdate, $login_old, $login_new, $password_new, $img_name);

    $_SESSION["login"] = $login_new;
    $user = selectUserByLogin($_SESSION["login"]);
    $user_id = $user["id"];

    header("Location: /profile.php?user_id=" .$user_id);
?>