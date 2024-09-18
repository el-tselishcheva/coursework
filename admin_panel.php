<?php
    include "header.php";
    $login = !empty($_SESSION['login']) ? $_SESSION['login'] : null;
    $user = !empty($login) ? selectUserByLogin($login) : null;
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Панель администратора</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="card mb-4 py-3 px-4">
                <h2>Панель администратора</h2>
                <ul class="nav nav-underline mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane" type="button" role="tab" aria-controls="bio-tab-pane" aria-selected="true">Пользователи</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="books-tab" data-bs-toggle="tab" data-bs-target="#books-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="false">Книги</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="authors-tab" data-bs-toggle="tab" data-bs-target="#authors-tab-pane" type="button" role="tab" aria-controls="lists-tab-pane" aria-selected="false">Авторы</button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="users-tab-pane" role="tabpanel" aria-labelledby="users-tab" tabindex="0">
                    </div>

                    <div class="tab-pane fade" id="books-tab-pane" role="tabpanel" aria-labelledby="books-tab" tabindex="0">
                    </div>

                    <div class="tab-pane fade" id="authors-tab-pane" role="tabpanel" aria-labelledby="authors-tab" tabindex="0">
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                showUsers();
                showBooks();
                showAuthors();
            });

            function selectOption(user_id) {
                var this_modal_id = "#select-user-modal-" + user_id;
                $(this_modal_id).modal('hide');

                var input_id = "#input-option-" + user_id;
                var new_modal_id = $(input_id).val();
                $(new_modal_id).modal('show');
            };

            function showUsers() {
                $.ajax({
                    url: 'ajax.php?action=show_users',
                    data: {},
                    success: function(data) {
                        $("#users-tab-pane").html(data);
                    }
                });
            };

            function showBooks() {
                $.ajax({
                    url: 'ajax.php?action=show_books',
                    data: {},
                    success: function(data) {
                        $("#books-tab-pane").html(data);
                    }
                });
            };

            function showAuthors() {
                $.ajax({
                    url: 'ajax.php?action=show_authors',
                    data: {},
                    success: function(data) {
                        $("#authors-tab-pane").html(data);
                    }
                });
            };

            function makeAdmin(user_id) {
                $.ajax({
                    url: 'ajax.php?action=make_admin',
                    method: 'POST',
                    data: {id: user_id}
                }).done(function(data) {user_id});

                hideMakeAdminModal(user_id);
                showUsers();
            };

            function banUser(user_id) {
                $.ajax({
                    url: 'ajax.php?action=ban_user',
                    method: 'POST',
                    data: {id: user_id}
                }).done(function(data) {user_id});

                hideBanUserModal(user_id);
                showUsers();
            };

            function unbanUser(user_id) {
                $.ajax({
                    url: 'ajax.php?action=unban_user',
                    method: 'POST',
                    data: {id: user_id}
                }).done(function(data) {user_id});

                hideUnbanUserModal(user_id);
                showUsers();
            };

            function deleteUser(user_id) {
                $.ajax({
                    url: 'ajax.php?action=delete_user',
                    method: 'POST',
                    data: {id: user_id}
                }).done(function(data) {user_id});

                hideDeleteUserModal(user_id);
                showUsers();
            };

            function hideMakeAdminModal(user_id) {
                var modal_id = "#make-admin-user-modal-" + user_id;
                $(modal_id).modal('hide');
            };

            function hideBanUserModal(user_id) {
                var modal_id = "#ban-user-modal-" + user_id;
                $(modal_id).modal('hide');
            };

            function hideUnbanUserModal(user_id) {
                var modal_id = "#unban-user-modal-" + user_id;
                $(modal_id).modal('hide');
            };

            function hideDeleteUserModal(user_id) {
                var modal_id = "#delete-user-modal-" + user_id;
                $(modal_id).modal('hide');
            };

            // const stars_arr = document.querySelectorAll(".stars");
            // const ratings = document.querySelectorAll(".rating");
            // showRating(ratings);

            // function showRating(ratings) {
            //     stars_arr.forEach((stars, i) => {
            //         let rating = ratings[i].textContent * 2;
            //         let star_arr = stars.childNodes;
            //         for (j = 1; j < star_arr.length; j += 2) {
            //             console.log(j);
            //             console.log(rating);
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