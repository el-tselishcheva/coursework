<?php
    // include "login_validation.php";
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Авторизация</title>
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
                    <h2 class="text-center">Авторизация</h2>

                    <form action="/login_validation.php" id="login-form" method="post">
                        <div class="row g-3" novalidate>
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control" id="login" name="login" placeholder="логин" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="пароль" required>
                                </div>
                                <div class="text-danger" id="no-success-login" style="display: none; padding-left: 52px;"></div>
                            </div>

                            <div class="auth-btn d-flex justify-content-center">
                                <!-- <button type="submit" class="btn btn-primary" name="log_in">Войти</button> -->
                                <button class="btn btn-primary" id="log-in-btn">Войти</button>
                                <p hidden name="log_in"></p>
                            </div>

                            <p class="auth-txt text-center text-muted">Еще нет аккаунта?
                                <a href="/signup.php" class="auth-redirect fw-bold">Зарегистрироваться</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                let check = false;

                $('#login-form').submit(function(e) {
                    return check;
                })

                $('#log-in-btn').click(function(e) {
                    let check_login = $("#login").val();
                    let check_password = $("#password").val();
                    
                    $.ajax({
                        url: 'ajax.php?action=login_check',
                        type: 'POST',
                        data: {
                            login: check_login,
                            password: check_password
                        },
                        success: function (data) {
                            data = JSON.parse(data);
                            if (!data['is_banned'] && !data['attempt']) {
                                check = false;
                                $('#no-success-login').show();
                                $('#no-success-login').html('Неверный логин или пароль.');
                            } else if (data['is_banned']) {
                                check = false;
                                $('#no-success-login').show();
                                $('#no-success-login').html('Отказано в доступе.');
                            } else {
                                check = true;
                                $('#no-success-login').hide();
                                $('#login-form').submit();
                            }
                        }
                    })
                })
            })
        </script>
    </body>
</html>