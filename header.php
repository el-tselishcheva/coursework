<?php
    include "db.php";
    $curr_url = $_SERVER['REQUEST_URI'];
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script> -->
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <header class="py-3 mb-4 border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-start">
                        <a href="/" class="d-flex pe-3 link-body-emphasis text-decoration-none">
                            <i class="fa-solid fa-book fa-xl"></i>
                        </a>
                        
                        <ul class="nav">
                            <li><a href="#" class="nav-link px-2 link-body-emphasis">Книги</a></li>
                            <li><a href="#" class="nav-link px-2 link-body-emphasis">Авторы</a></li>
                            <li><a href="#" class="nav-link px-2 link-body-emphasis">Жанры</a></li>
                        </ul>

                    <?php
                        session_start();
                        $login = !empty($_SESSION['login']) ? $_SESSION['login'] : null;
                        $user = selectUserByLogin($login);
                        $check = is_array($user);
                        
                        if (!$check) { 
                            $_SESSION['login'] = null; ?>

                            <ul class="nav ms-auto">
                                <li class="nav-item pe-3"><button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#log-in-modal">Войти</button></li>
                                <li class="nav-item"><a href="/signup.php" class="btn btn-outline-primary">Зарегистрироваться</a></li>
                            </ul>

                            <div class="modal fade" id="log-in-modal" tabindex="-1" aria-labelledby="log-in-modal-label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content rounded-4 shadow">
                                        <div class="modal-header p-5 pb-4 border-bottom-0">
                                            <h2 class="fw-bold mb-0 fs-2">Авторизация</h2>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                        </div>

                                        <div class="modal-body p-5 pt-0">
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
                                                        <button class="btn btn-primary" id="log-in-btn">Войти</button>
                                                        <input hidden name="log_in" value="<?=$curr_url?>">
                                                    </div>

                                                    <p class="auth-txt text-center text-muted">Еще нет аккаунта?
                                                        <a href="/signup.php" class="auth-redirect fw-bold">Зарегистрироваться</a>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <?php } else { ?>

                            <div class="dropdown ms-auto">
                                <button class="btn btn-link d-block link-dark text-decoration-none dropdown-toggle p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="/img/<?=$user['profile_picture']?>" alt="" width="32" height="32" class="rounded-circle object-fit-cover bg-body-secondary">
                                    <p hidden id="curr_user_id"><?=$user['id']?></p>
                                </button>
                                <ul class="dropdown-menu text-small shadow">
                                    <li><a class="dropdown-item" href="/profile.php?user_id=<?=$user['id']?>">Профиль</a></li>
                                    <?php if ($user['is_admin']) { ?>
                                        <li><a class="dropdown-item" href="/admin_panel.php">Панель администратора</a></li>
                                        <li><a class="dropdown-item" href="/admin_accounting.php">Отчетность</a></li>
                                    <?php } ?>
                                    <li><a class="dropdown-item" href="#">Лента обновлений</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <form action="/signout_handler.php" method="post" class="m-0">
                                        <li><button type="submit" class="btn dropdown-item" name="sign_out" value="<?=$curr_url?>">Выйти</a></li>
                                    </form>
                                </ul>
                            </div>
                    <?php }; ?>
                </div>
            </div>
        </header>

        <script type="text/javascript">
            $(document).ready(function() {
                let check = false;

                $('#login-form').submit(function(e) {
                    return check;
                })

                $('#log-in-btn').click(function(e) {
                    let check_login = $("#login").val();
                    let check_password = $("#password").val();
                    
                    if (check_login != '' && check_password != '') {
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
                    }
                })
            })
        </script>
    </body>
</html>