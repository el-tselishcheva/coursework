<?php
    include "header.php";

    $login = !empty($_SESSION['login']) ? $_SESSION['login'] : null;
    $curr_user = !empty($login) ? selectUserByLogin($login) : null;
    $curr_user_id = is_array($curr_user) ? $curr_user['id'] : 0;

    $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : null;
    $user = selectAllById('users', $user_id);
    $reviews = selectBookReviewsByUserId($user['id']);
    $followings = selectFollowingsByUserId($user['id']);
    $lists = selectListsByUserId($user['id']);

    $gender = selectAllById('genders', $user['gender_id'])['name'];
    $birthdate = date_format(date_create($user['birthdate']), 'M j, Y');
    $following_number = getNumberOfUserFollowings($user['id'])['total_number'];
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=$user['name']?> <?=$user['surname']?></title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="card mb-4 p-3">
                <div class="row mx-0">
                    <div class="col-auto text-center">
                        <img src="/img/<?=$user['profile_picture']?>" alt="" width="128" height="128" class="rounded-circle object-fit-cover">
                    </div>
                    <div class="col align-self-center">
                        <h4><?=$user['name']?> <?=$user['surname']?></h4>
                        <p class="text-muted">@<?=$user['login']?></p>
                        <p hidden id="user_id"><?=$user['id']?></p>
                    </div>
                    <?php if ($user['login'] == $_SESSION["login"]) { ?>
                        <div class="col-auto align-self-center">
                            <a class="btn btn-primary" href="/profile_edit.php" role="button">Редактировать</a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="card mb-4 py-3 px-4">
                <ul class="nav nav-underline mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="bio-tab" data-bs-toggle="tab" data-bs-target="#bio-tab-pane" type="button" role="tab" aria-controls="bio-tab-pane" aria-selected="true">О пользователе</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="false">Отзывы</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="following-tab" data-bs-toggle="tab" data-bs-target="#following-tab-pane" type="button" role="tab" aria-controls="following-tab-pane" aria-selected="false">Подписки</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lists-tab" data-bs-toggle="tab" data-bs-target="#lists-tab-pane" type="button" role="tab" aria-controls="lists-tab-pane" aria-selected="false">Списки</button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="bio-tab-pane" role="tabpanel" aria-labelledby="bio-tab" tabindex="0">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Полное имя</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?=$user['name']?> <?=$user['surname']?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Пол</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?=$gender?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <p class="mb-0">Дата рождения</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?=$birthdate?></p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                        <?php if (empty($reviews)) { ?>
                            <p class="text-muted">Нет отзывов.</p>
                        <?php } ?>

                        <ul class="list-group list-group-flush">
                        <?php foreach ($reviews as $i=>$review):
                            $posting_time = date_format(date_create($review['posting_time']), 'M j, Y H:i:s'); ?>
                            <!-- <div class="row mb-3">
                                <div class="col-auto">
                                    <img src="/img/<?=$review['image']?>" alt="" width="64" height="64" class="rounded object-fit-cover">
                                </div>
                                <div class="col">
                                    <form action="/book.php" method="get" class="mb-2">
                                        <button type="submit" name="book_id" value="<?=$review['book_id']?>" class="btn btn-link text-decoration-none fw-medium m-0 p-0">
                                            <?=$review['book_name']?>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-auto">
                                    <p class="text-muted mb-0"><?=$posting_time?></p>
                                </div>
                            </div>
                            <?php if ($i + 1 == count($reviews)) { ?>
                                <p><?=$review['review_text']?></p>
                            <?php } else { ?>
                                <p class="mb-0"><?=$review['review_text']?></p>
                                <hr>
                            <?php } ?> -->

                            <li class="list-group-item px-0">
                                <div class="row mb-3">
                                    <div class="col-auto">
                                    <img src="/img/<?=$review['image']?>" alt="" width="64" height="64" class="rounded object-fit-cover">
                                    </div>
                                    <div class="col">
                                        <p class="mb-2">
                                            <a href="/book.php?book_id=<?=$review['book_id']?>" class="link-body-emphasis text-decoration-none text-start fw-medium m-0 p-0">
                                                <?=$review['book_name']?>
                                            </a>
                                        </p>
                                        <p class="text-muted m-0"><?=$posting_time?></p>
                                    </div>
                                </div>
                                <p><?=$review['review_text']?></p>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="tab-pane fade" id="following-tab-pane" role="tabpanel" aria-labelledby="following-tab" tabindex="0">
                        <div class="following-form">
                            <?php if ($following_number == 0) { ?>
                                <p class="text-muted">Нет подписок.</p>
                            <?php }
                            foreach ($followings as $i=>$following): ?>
                                <div class="row mb-3">
                                    <div class="col-auto">
                                        <img src="/img/<?=$following['image']?>" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
                                    </div>
                                    <div class="col">
                                        <form action="/author.php" method="get" class="mb-2">
                                            <button type="submit" name="author_id" value="<?=$following['id']?>" class="btn btn-link text-decoration-none fw-medium m-0 p-0">
                                                <?=$following['name']?>
                                            </button>
                                            <p class="text-muted">автор</p>
                                        </form>
                                    </div>
                                </div>
                                <?php if ($i + 1 != $following_number) { ?>
                                    <hr>
                                <?php }
                            endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="lists-tab-pane" role="tabpanel" aria-labelledby="lists-tab" tabindex="0">
                        <?php if ($curr_user_id == $user_id) { ?>
                            <div class="d-flex flex-row-reverse mb-3">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#new-list-modal">Создать</button>
                            </div>
                            <div class="modal fade" id="new-list-modal" tabindex="-1" aria-labelledby="new-list-modal-label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content rounded-4 shadow">
                                        <div class="modal-header p-5 pb-4 border-bottom-0">
                                            <h2 class="fw-bold mb-0 fs-2">Новый список</h2>
                                            <button type="button" class="btn-close" id="new-list-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-5 pt-0">
                                            <form id="create-new-list-form">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="create-list-name" id="create-list-name" placeholder="название списка" required>
                                                </div>
                                                <div class="text-danger" id="list-exists" style="display: none; padding-left: 12px;">
                                                    Список с таким названием уже существует.
                                                </div>
                                                <div class="mt-4 d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary" id="new-list-modal-submit">Создать</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }; ?>
                        <div class="list-form">
                            <?php foreach ($lists as $i=>$list):
                                $update_time = date_format(date_create($list['update_time']), 'M j, Y H:i:s');

                                if ($i != 0) { ?>
                                    <hr>
                                <?php }; ?>

                                <div class="px-3">
                                    <div class="row mb-3">
                                        <div class="col-auto pt-3 d-flex align-items-start">
                                            <span><i class="fa-solid fa-heart fa-2xl"></i></span>
                                        </div>
                                        <div class="col pe-4">
                                            <form action="/list.php" method="get" class="mb-0">
                                                <button type="submit" name="list_id" value="<?=$list['id']?>" class="btn btn-link text-start text-decoration-none fw-medium m-0 p-0">
                                                    <?=$list['name']?>
                                                </button>
                                            </form>
                                            <p class="text-muted mb-0"><?=$list['total_number']?> книг</p>
                                        </div>
                                        <div class="col-auto p-0">
                                            <p class="text-muted text-end mb-0">Обновлено</p>
                                            <p class="text-muted text-end mb-0"><?=$update_time?></p>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                let check = false;
                loadFollowings();
                loadLists();
                
                $('#create-new-list-form').submit(function(e) {
                    // if ($('#curr_user_id')[0].textContent == $('#user_id')[0].textContent) {
                    if (<?=$curr_user_id?> == <?=$user_id?>) {
                        // $.ajax({
                        //     url: 'ajax.php?action=create_list',
                        //     method: 'POST',
                        //     // data: {name: $('#list-name').val(), user_id: $('#user_id')[0].textContent},
                        //     data: {name: $('#create-list-name').val()},
                        //     success: function(data) {
                        //         $(".list-form").html(data);
                        //     }
                        // // }).done(function(data) {$('#list-name').val(), $('#user_id')[0].textContent});
                        // })//.done(function(data) {$('#create-list-name').val()});
                        // // $('#new-list-modal').modal('hide');
                        // // $('#new-list-modal-close').click();
                        // $('#create-new-list-form').trigger("reset");
                        // hideCreateListModal();
                        // loadLists();
                        // return false;

                        if (!check) {
                            loadLists();
                            return false;
                        } else {
                            $.ajax({
                                url: 'ajax.php?action=create_list',
                                method: 'POST',
                                data: {name: $('#create-list-name').val()},
                                success: function(data) {
                                    $(".list-form").html(data);
                                }
                            }).done(function(data) {$('#create-list-name').val()});

                            $('#create-new-list-form').trigger("reset");
                            $('#create-list-name').css('border-color', '#dee2e6');
                            $('#list-exists').hide();

                            hideCreateListModal();
                            loadLists();
                            return false;
                        }
                    }
                })

                // $('#create-new-list-form').submit(function(e) {

                // })

                $("#new-list-modal-close").click(function(e) {
                    $('#create-new-list-form').trigger("reset");
                    $('#create-list-name').css('border-color', '#dee2e6');
                    $('#list-exists').hide();
                })

                $('#create-list-name').on('keyup', function () {
                    var list_name = $("#create-list-name").val();

                    $.ajax({
                        url: 'ajax.php?action=check_list',
                        type: 'POST',
                        data: {list: list_name},
                        success: function (data) {
                            if ($("#create-list-name").val() == '') {
                                $('#create-list-name').css('border-color', '#dee2e6');
                                $('#list-exists').hide();
                                check = false;
                            } else if (data == 'true') {
                                $('#create-list-name').css('border-color', 'red');
                                $('#list-exists').show();
                                check = false;
                            } else if (data == 'false') {
                                $('#create-list-name').css('border-color', 'green');
                                $('#list-exists').hide();
                                check = true;
                            }
                        }
                    });
                })
            });

            function loadFollowings() {
                // if ($('#curr_user_id')[0].textContent == $('#user_id')[0].textContent) {
                if (<?=$curr_user_id?> == <?=$user_id?>) {
                    $.ajax({
                        url: 'ajax.php?action=update_followings',
                        method: 'POST',
                        data: {user_id: $('#user_id')[0].textContent},
                        success: function(data) {
                            $(".following-form").html(data);
                        }
                    }).done(function(data) {$('#user_id')[0].textContent});
                    return false;
                }
            }

            function loadLists() {
                // if ($('#curr_user_id')[0].textContent == $('#user_id')[0].textContent) {
                if (<?=$curr_user_id?> == <?=$user_id?>) {
                    $.ajax({
                        url: 'ajax.php?action=update_lists',
                        method: 'POST',
                        data: {user_id: $('#user_id')[0].textContent},
                        success: function(data) {
                            $(".list-form").html(data);
                        }
                    }).done(function(data) {$('#user_id')[0].textContent});
                    return false;
                }
            }

            function editListModal(list_id) {
                var input_name = "#edit-list-name-" + list_id;
                var list_name = "#edit-list-name-actual-" + list_id;
                var warning = "#list-exists-" + list_id;
                var name = $(list_name)[0].textContent

                $(input_name).css('border-color', '#dee2e6');
                $(input_name).val(name);
                $(warning).hide();
            }

            function editList(list_id) {
                var input_name = "#edit-list-name-" + list_id;
                var warning = "#list-exists-" + list_id;
                var new_name = $(input_name).val();

                $.ajax({
                    url: 'ajax.php?action=edit_list',
                    method: 'POST',
                    data: {id: list_id, name: new_name},
                    success: function(data) {
                        if (data == 'true') {
                            $(input_name).css('border-color', 'red');
                            $(warning).show();
                        } else {
                            $(input_name).css('border-color', '#dee2e6');
                            $(warning).hide();
                            hideEditListModal(list_id);
                            loadLists();
                        }
                        // $(".list-form").html(data);
                    }
                })//.done(function(data) {list_id, new_name});

                // hideEditListModal(list_id);
                // loadLists();
                return false;
            }

            function deleteList(list_id) {
                $.ajax({
                    url: 'ajax.php?action=delete_list',
                    method: 'POST',
                    data: {id: list_id},
                    // success: function(data) {
                    //     $(".list-form").html(data);
                    // }
                }).done(function(data) {list_id});

                hideDeleteListModal(list_id);
                loadLists();
            }

            function unfollow(user_id, author_id) {
                $.ajax({
                    url: 'ajax.php?action=unfollow',
                    method: 'POST',
                    data: {user_id: user_id, author_id: author_id},
                    // success: function(data) {
                    //     $(".list-form").html(data);
                    // }
                }).done(function(data) {user_id, author_id});

                hideUnfollowModal(author_id);
                loadFollowings();
            }

            function hideCreateListModal() {
                $('#new-list-modal').modal('hide');
            }

            function hideEditListModal(list_id) {
                var modal_id = "#edit-list-modal-" + list_id;
                $(modal_id).modal('hide');
            }

            function hideUnfollowModal(author_id) {
                var modal_id = "#unfollow-modal-" + author_id;
                $(modal_id).modal('hide');
            }

            function hideDeleteListModal(list_id) {
                var modal_id = "#delete-list-modal-" + list_id;
                $(modal_id).modal('hide');
            }

            // const stars_arr = document.querySelectorAll(".stars");
            // const ratings = document.querySelectorAll(".rating");
            // showRating(ratings);

            // function showRating(ratings) {
            //     stars_arr.forEach((stars, i) => {
            //         let rating = ratings[i].textContent * 2;
            //         let star_arr = stars.childNodes;
            //         for (j = 1; j < star_arr.length; j += 2) {
            //             if (j <= rating) {
            //                 star_arr[j].classList.add("selected");
            //             } else {
            //                 star_arr[j].classList.remove("selected");
            //             }
            //         }
            //     });
            // }
        </script>
    </body>
</html>