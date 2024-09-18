<?php
    include "header.php";
    $login = !empty($_SESSION['login']) ? $_SESSION['login'] : null;
    $curr_user_id = !empty($login) ? selectUserByLogin($login)['id'] : null;

    $id = !empty($_GET['list_id']) ? $_GET['list_id'] : null;
    $list = selectListInfoById($id);
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=$list['list_name']?></title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="card m-0 p-3">
                <div class="row" id="list-info-row">
                </div>
            </div>

            <div class="container p-0 mb-3">
                <!-- <h2 class="ps-4">Книги автора</h2> -->
                <div class="row" id="listed-books-row">
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                loadListInfo();
                loadBooks();
            });

            function editListModal() {
                var input_name = "#edit-list-name";
                var list_name = "#edit-list-name-actual";
                var warning = "#list-exists";
                var name = $(list_name)[0].textContent

                $(input_name).css('border-color', '#dee2e6');
                $(input_name).val(name);
                $(warning).hide();
            }

            function editList(list_id) {
                var input_name = "#edit-list-name";
                var warning = "#list-exists";
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
                            $('#edit-list-modal').modal('hide');
                            loadListInfo();
                        }
                    }
                })

                return false;
            }

            function deleteList(list_id) {
                $.ajax({
                    url: 'ajax.php?action=delete_list',
                    method: 'POST',
                    data: {id: list_id}
                }).done(function(data) {list_id});

                $('#delete-list-modal').modal('hide');
                location.href = 'profile.php?user_id=<?=$curr_user_id?>';
            }

            function hideDeleteListModal() {
                $('#delete-list-modal').modal('hide');
            }

            function deleteBook(book_id) {
                $.ajax({
                    url: 'ajax.php?action=delete_book_from_list',
                    method: 'POST',
                    data: {id: book_id, list_id: <?=$list['id']?>},
                }).done(function(data) {book_id, <?=$list['id']?>});

                hideDeleteBookModal(book_id);
                loadBooks();
            }

            function hideDeleteBookModal(book_id) {
                var modal_id = "#delete-book-modal-" + book_id;
                $(modal_id).modal('hide');
            }

            function loadListInfo() {
                $.ajax({
                    url: 'ajax.php?action=update_list_info',
                    method: 'POST',
                    data: {
                        list_id: <?=$list['id']?>
                    },
                    success: function(data) {
                        $("#list-info-row").html(data);
                    }
                }).done(function(data) {<?=$list['id']?>});
                return false;
            }

            function loadBooks() {
                $.ajax({
                    url: 'ajax.php?action=update_list_books',
                    method: 'POST',
                    data: {
                        list_id: <?=$list['id']?>
                    },
                    success: function(data) {
                        $("#listed-books-row").html(data);
                    }
                }).done(function(data) {<?=$list['id']?>});
                return false;
            }

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