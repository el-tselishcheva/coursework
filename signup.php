<?php
    include "signup_validation.php";
    $genders = selectAllGenders();
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Регистрация</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="auth container">
            <div class="row mt-5">
                <div class="col-lg-6 m-auto">
                    <h2 class="text-center">Регистрация</h2>

                    <form action="/signup_validation.php" id="signup-form" method="post">
                        <div class="row g-3" novalidate>
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="input_name" name="name" placeholder="Имя" required>
                                    <label for="input_name">Имя</label>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="input_surname" name="surname" placeholder="Фамилия" required>
                                    <label for="input_surname">Фамилия</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-floating">
                                    <select class="form-select" id="input_gender" name="gender_id" required>
                                        <?php foreach ($genders as $gender): ?>
                                            <option value="<?=$gender['id']?>"><?=$gender['name']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="input_gender">Пол</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="input_birthdate" name="birthdate" required/>
                                    <label for="input_birthdate">Дата рождения</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control" id="login" name="login" placeholder="логин" required>
                                </div>
                                <div class="text-danger" id="login-exists" style="display: none; padding-left: 52px;">
                                    Логин занят.
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="пароль" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm-password" placeholder="подтвердите пароль" required>
                                </div>
                                <div class="text-danger" id="passwords-do-not-match" style="display: none; padding-left: 52px;">
                                    Пароли не совпадают.
                                </div>
                            </div>

                            <div class="auth-btn d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" name="sign_up">Зарегистрироваться</button>
                            </div>

                            <p class="auth-txt text-center text-muted">Уже есть аккаунт?
                                <a href="/login.php" class="auth-redirect fw-bold">Войти</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                var check_log = false;
                var check_pass = false;
                var check = check_log && check_pass;

                $('#signup-form').submit(function(e) {
                    check = check_log && check_pass;

                    if (!check) {
                        return false;
                    }
                });

                $('#login').on('keyup', function () {
                    var check_login = $("#login").val();

                    $.ajax({
                        url: 'ajax.php?action=check_login',
                        type: 'POST',
                        data: {login: check_login},
                        success: function (data) {
                            if ($("#login").val() == '') {
                                $('#login').css('border-color', '#dee2e6');
                                $('#login-exists').hide();
                                check_log = false;
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

                $('#password, #confirm-password').on('keyup', function () {
                    if ($('#password').val() == '' && $('#confirm-password').val() == '') {
                        $('#passwords-do-not-match').hide();
                        $('#password').css('border-color', '#dee2e6');
                        $('#confirm-password').css('border-color', '#dee2e6');
                        check_pass = false;
                    } else if ($('#password').val() == $('#confirm-password').val()) {
                        $('#passwords-do-not-match').hide();
                        $('#password').css('border-color', 'green');
                        $('#confirm-password').css('border-color', 'green');
                        check_pass = true;
                    } else {
                        $('#passwords-do-not-match').show();
                        $('#password').css('border-color', 'red');
                        $('#confirm-password').css('border-color', 'red');
                        check_pass = false;
                    }
                });
            })
        </script>
    </body>
</html>