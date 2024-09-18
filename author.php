<?php
    include "header.php";
    $login = !empty($_SESSION['login']) ? $_SESSION['login'] : null;

    $id = !empty($_GET['author_id']) ? $_GET['author_id'] : null;
    $author = !empty($id) ? selectAllById('authors', $id) : null;
    $books = !empty($id) ? selectBooksByAuthorId($id) : null;
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=$author['name']?></title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="card mb-4 p-3">
                <div class="row mx-0">
                    <div class="col-auto text-center">
                        <img src="img/<?=$author['image']?>" alt="" width="128" height="128" class="rounded-circle object-fit-cover">
                    </div>
                    <div class="col align-self-center">
                        <h4><?=$author['name']?></h4>
                        <p class="text-muted">автор</p>
                        <p hidden id="author-id"><?=$author['id']?></p>
                        <div id="author-followers"></div>
                    </div>
                    <?php if ($login != null) { ?>
                        <div class="col-auto align-self-center" id="follow-author-button"></div>
                    <?php }; ?>
                </div>
            </div>

            <div class="card mb-4 py-3 px-4">
                <ul class="nav nav-underline mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="bio-tab" data-bs-toggle="tab" data-bs-target="#bio-tab-pane" type="button" role="tab" aria-controls="bio-tab-pane" aria-selected="true">Об авторе</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="false">Отзывы</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lists-tab" data-bs-toggle="tab" data-bs-target="#lists-tab-pane" type="button" role="tab" aria-controls="lists-tab-pane" aria-selected="false">Списки</button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="bio-tab-pane" role="tabpanel" aria-labelledby="bio-tab" tabindex="0">
                        <?=$author['description']?>
                    </div>

                    <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                        2
                    </div>

                    <div class="tab-pane fade" id="lists-tab-pane" role="tabpanel" aria-labelledby="lists-tab" tabindex="0">
                        3
                    </div>
                </div>
            </div>

            <div class="container p-0 mb-4">
                <h4 class="ps-4 m-0">Книги автора</h4>
                <div class="row">
                    <?php foreach ($books as $book): ?>
                        <div class="col-lg-2 col-sm-4 p-3">
                            <div class="card h-100">
                                <img src="img/<?=$book['book_cover']?>" alt="" class="card-img-top object-fit-contain">
                                <div class="card-body">
                                    <a href="/book.php?book_id=<?=$book['book_id']?>" class="link-body-emphasis text-decoration-none text-start m-0 p-0">
                                        <h5 class="mb-1 p-0"><?=$book['book_name']?></h5>
                                    </a>
                                    <?php $authors = selectAuthorsByBookId($book['book_id']); ?>
                                    <div class="d-flex flex-column">
                                        <?php foreach ($authors as $author): ?>
                                            <a href="/author.php?author_id=<?=$author['author_id']?>" class="link-body-emphasis text-decoration-none text-start m-0 p-0">
                                                <?=$author['author']?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                showFollowers();
                showFollowButton();
            });

            function showFollowers() {
                $.ajax({
                    url: 'ajax.php?action=show_author_followers',
                    method: 'POST',
                    data: {author_id: $('#author-id')[0].textContent},
                    success: function(data) {
                        $("#author-followers").html(data);
                    }
                });
            };

            function showFollowButton() {
                $.ajax({
                    url: 'ajax.php?action=show_follow_author_button',
                    method: 'POST',
                    data: {user_id: $('#curr_user_id')[0].textContent, author_id: $('#author-id')[0].textContent},
                    success: function(data) {
                        $("#follow-author-button").html(data);
                    }
                });
            };

            function clickFollow() {
                $.ajax({
                    url: 'ajax.php?action=start_following',
                    method: 'POST',
                    data: {user_id: $('#curr_user_id')[0].textContent, author_id: $('#author-id')[0].textContent}
                }).done(function(data) {$('#curr_user_id')[0].textContent, $('#author-id')[0].textContent});

                showFollowers();
                showFollowButton();
                return false;
            };

            function clickUnfollow() {
                $.ajax({
                    url: 'ajax.php?action=stop_following',
                    method: 'POST',
                    data: {user_id: $('#curr_user_id')[0].textContent, author_id: $('#author-id')[0].textContent}
                }).done(function(data) {$('#curr_user_id')[0].textContent, $('#author-id')[0].textContent});

                showFollowers();
                showFollowButton();
                return false;
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