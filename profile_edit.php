<?php
    include "header.php";

    // session_start();
    $login = $_SESSION["login"];

    $user = selectUserByLogin($login);
    $genders = selectAllGenders();
    $birthdate = date_format(date_create($user['birthdate']), 'Y-m-d');
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Профиль</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <form action="/profile_edit_handler.php" method="post" id="update-profile-info" enctype="multipart/form-data">
                <div class="row" novalidate>
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <span id="pfp-handler">
                                    <img src="/img/<?=$user['profile_picture']?>" alt="" class="pfp-img rounded-circle bg-body-secondary">
                                </span>
                                <div class="input-group mt-4">
                                    <input type="file" class="form-control" id="input-img" name="img"/>
                                    <!-- <label class="input-group-text" for="input_img">Загрузить</label> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="input_name" name="name" placeholder="Имя" value="<?=$user['name']?>" required>
                                            <label for="input_name">Имя</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="input_surname" name="surname" placeholder="Фамилия" value="<?=$user['surname']?>" required>
                                            <label for="input_surname">Фамилия</label>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="input_gender" name="gender_id" required>
                                                <?php foreach ($genders as $gender): 
                                                    if ($gender['id'] == $user['gender_id']) { ?>
                                                        <option selected value="<?=$gender['id']?>"><?=$gender['name']?></option>
                                                <?php } else { ?>
                                                        <option value="<?=$gender['id']?>"><?=$gender['name']?></option>
                                                <?php }
                                                endforeach; ?>
                                            </select>
                                            <label for="input_gender">Пол</label>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="input_birthdate" name="birthdate" value="<?=$birthdate?>" required/>
                                            <label for="input_birthdate">Дата рождения</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" class="form-control" id="login" name="login" placeholder="логин" value="<?=$user['login']?>" required>
                                        </div>
                                        <div class="text-danger" id="login-exists" style="display: none; padding-left: 52px;">
                                            Логин занят.
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password-old" name="password_old" placeholder="старый пароль">
                                        </div>
                                        <div class="text-danger" id="password-wrong" style="display: none; padding-left: 52px;">
                                            Неверный пароль.
                                        </div>
                                        <div class="text-danger" id="password-require" style="display: none; padding-left: 52px;">
                                            Введите старый пароль.
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password-new" name="password_new" placeholder="новый пароль">
                                        </div>
                                        <div class="text-danger" id="passwords-match" style="display: none; padding-left: 52px;">
                                            Старый и новый пароли не должны совпадать.
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                            <input type="password" class="form-control" id="confirm-password-new" placeholder="подтвердите новый пароль">
                                        </div>
                                        <div class="text-danger" id="passwords-do-not-match" style="display: none; padding-left: 52px;">
                                            Пароли не совпадают.
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                var file_arr = [];
                var file_names = []
                var i = 0;

                $(document).on('change', '#input-img', function() {
                    var name = document.getElementById("input-img").files[0].name;
                    var form_data = new FormData();
                    var ext = name.split('.').pop().toLowerCase();
                    if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                        alert("Недопустимое расширение файла.");
                    }
                    var oFReader = new FileReader();
                    oFReader.readAsDataURL(document.getElementById("input-img").files[0]);
                    var f = document.getElementById("input-img").files[0];
                    var fsize = f.size || f.fileSize;
                    if (fsize > 104857600) {
                        alert("Размер изображения не должен превышать 100Мб.");
                    } else {
                        form_data.append("img", document.getElementById('input-img').files[0]);
                        file_arr[i] = document.getElementById('input-img').files[0];
                        file_names[i] = document.getElementById('input-img').files[0].name;
                        ++i;
                        $.ajax({
                            url: "ajax.php?action=image_preview",
                            method: "POST",
                            data: form_data,
                            contentType: false,
                            cache: false,
                            processData: false,
                            beforeSend: function() {
                                $('#pfp-handler').html("<label class='text-success'>Загрузка изображения...</label>");
                            },
                            success: function(data) {
                                $('#pfp-handler').html(data);
                                var new_name = $('#img-path').text();
                                file_names[file_names.length - 1] = new_name;
                            }
                        });
                    }
                });

                var check_log = true;
                var check_pass_old = true;
                var check_pass_new = true;
                var check = check_log && check_pass_old && check_pass_new;

                $('#login').on('keyup', function () {
                    var check_login = $("#login").val();

                    $.ajax({
                        url: 'ajax.php?action=check_login',
                        type: 'POST',
                        data: {login: check_login},
                        success: function (data) {
                            if ($("#login").val() == '' || $("#login").val() == '<?=$login?>') {
                                $('#login').css('border-color', '#dee2e6');
                                $('#login-exists').hide();
                                check_log = true;
                            } else if (data == 'true') {
                                $('#login').css('border-color', 'red');
                                $('#login-exists').show();
                                check_log = false;
                            } else if (data == 'false') {
                                $('#login').css('border-color', 'green');
                                $('#login-exists').hide();
                                check_log = true;
                            }
                        }
                    });
                })

                $('#password-old').on('keyup', function () {
                    var check_password = $("#password-old").val();

                    $.ajax({
                        url: 'ajax.php?action=check_password',
                        type: 'POST',
                        data: {password: check_password},
                        success: function (data) {
                            if ($("#password-old").val() == '') {
                                $('#password-old').css('border-color', '#dee2e6');
                                $('#password-wrong').hide();
                                check_log = true;
                            } else if (data == 'false') {
                                $('#password-old').css('border-color', 'red');
                                $('#password-wrong').show();
                                check_log = false;
                            } else if (data == 'true') {
                                $('#password-old').css('border-color', 'green');
                                $('#password-wrong').hide();
                                check_log = true;
                            }
                        }
                    });
                })

                $('#password-new').on('keyup', function () {
                    var check_password = $("#password-new").val();

                    $.ajax({
                        url: 'ajax.php?action=check_password',
                        type: 'POST',
                        data: {password: check_password},
                        success: function (data) {
                            if ($("#password-new").val() == '') {
                                $('#password-new').css('border-color', '#dee2e6');
                                $('#passwords-match').hide();
                                check_log = true;
                            } else if (data == 'true') {
                                $('#password-new').css('border-color', 'red');
                                $('#passwords-match').show();
                                check_log = false;
                            } else if (data == 'false') {
                                $('#password-new').css('border-color', 'green');
                                $('#passwords-match').hide();
                                check_log = true;
                            }
                        }
                    });
                });

                $('#password-old, #password-new').on('keyup', function () {
                    if ($("#password-old").val() == '' && $("#password-new").val() != '') {
                        $('#password-old').css('border-color', 'red');
                        $('#password-require').show();
                        check_pass_old = false;
                    } else if ($("#password-old").val() != '') {
                        $('#password-require').hide();
                        check_pass_old = true;
                    }
                })

                $('#password-new, #confirm-password-new').on('keyup', function () {
                    if ($('#password-new').val() == '' && $('#confirm-password-new').val() == '') {
                        $('#passwords-do-not-match').hide();
                        $('#password-new').css('border-color', '#dee2e6');
                        $('#confirm-password-new').css('border-color', '#dee2e6');
                        check_pass_new = true;
                    } else if ($('#password-new').val() == $('#confirm-password-new').val()) {
                        $('#passwords-do-not-match').hide();
                        $('#password-new').css('border-color', 'green');
                        $('#confirm-password-new').css('border-color', 'green');
                        check_pass_new = true;
                    } else {
                        $('#passwords-do-not-match').show();
                        $('#password-new').css('border-color', 'red');
                        $('#confirm-password-new').css('border-color', 'red');
                        check_pass_new = false;
                    }
                });

                $('#update-profile-info').submit(function(e) {
                    check = check_log && check_pass_old && check_pass_new;

                    if (!check) {
                        return false;
                    } else {
                        $.ajax({
                            url: 'ajax.php?action=delete_images',
                            method: 'POST',
                            data: {files: file_names}
                        });
                    }
                });
            });
        </script>
    </body>
</html>