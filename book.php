<?php
    include "header.php";
    
    $login = !empty($_SESSION['login']) ? $_SESSION['login'] : null;

    $id = !empty($_GET['book_id']) ? $_GET['book_id'] : null;
    $book = !empty($id) ? selectAllById('books', $id) : null;
    $authors = !empty($id) ? selectAuthorsByBookId($id) : null;
    $genres = !empty($id) ? selectGenresByBookId($id) : null;
    $chapters = !empty($id) ? selectChaptersByBookId($id) : null;
    $reviews = !empty($id) ? selectReviewsByBookId($id) : null;

    $user = null;
    if ($login != null) {
        $user = selectUserByLogin($login);
    }

    $rating_number = getNumberOfBookRatings($id)['count'];
    $rating_average = getAverageOfBookRatings($id)['avg'];
    $rating = !empty($rating_average) ? round($rating_average, 2) : 0;
    $rating_star = !empty($rating_average) ? round($rating_average) : 0;
    $chapters_number = getNumberOfChapters($id)['getnumberofchapters'];
    $start_reading_url = "/reader.php?book_id=" .$id. "&chapter_number=1";
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=$book['name']?></title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="row mb-4">
                <div class="col-3">
                    <div class="rounded border mb-4">
                        <img src="img/<?=$book['image']?>" alt="" class="img-fluid rounded">
                    </div>
                    <div class="mb-4">
                        <a href="<?=$start_reading_url?>" class="mb-4">
                            <button type="submit" id="start-reading-btn" class="col-12 btn btn-primary">Читать</button>
                        </a>
                    </div>

                    <?php if ($login != null) { ?>
                        <div class="mb-4">
                            <button class="col-12 btn btn-primary text-wrap dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Добавить в список
                            </button>

                            <ul class="dropdown-menu shadow" id="add-to-list-dropdown">
                                <li>
                                    <button type="button" class="btn btn-link dropdown-item" data-bs-toggle="modal" data-bs-target="#select-list-modal">Выбрать список</button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="btn btn-link dropdown-item" data-bs-toggle="modal" data-bs-target="#new-list-modal">Новый</button>
                                </li>
                            </ul>
                        </div>

                        <div class="modal fade" id="new-list-modal" tabindex="-1" aria-labelledby="new-list-modal-label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h2 class="fw-bold mb-0 fs-2">Новый список</h2>
                                        <button type="button" class="btn-close" id="new-list-modal-close" data-bs-dismiss="modal" aria-label="close"></button>
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
                                                <button class="btn btn-primary" id="new-list-modal-submit">Создать</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="select-list-modal" tabindex="-1" aria-labelledby="select-list-modal-label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h2 class="fw-bold mb-0 fs-2">Выберите список</h2>
                                        <button type="button" class="btn-close" id="select-list-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body p-5 pt-0">
                                        <div class="d-flex flex-column align-items-center">
                                            <select class="form-select mb-4" id="input-list"></select>
                                            <button class="yes-select-list btn btn-primary col-3" id="yes-select-list">Выбрать</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>
                </div>

                <div class="col-9">
                    <div class="card py-3 px-4 mb-4">
                        <div class="row">
                            <div class="col">
                                <h2 class="mb-1 p-0"><?=$book['name']?></h2>
                                <p hidden id="book-id"><?=$book['id']?></p>
                                <div id="book-rating-info"></div>
                            </div>
                            <?php if ($login != null) { ?>
                                <div class="col-auto pt-1">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rate-book-modal">Оценить</button>
                                </div>

                                <div class="modal fade" id="rate-book-modal" tabindex="-1" aria-labelledby="rate-book-modal-label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content rounded-4 shadow">
                                            <div class="modal-header p-5 pb-4 border-bottom-0">
                                                <h2 class="fw-bold mb-0 fs-2">Оценить книгу</h2>
                                                <button type="button" class="btn-close" id="rate-book-modal-close" data-bs-dismiss="modal" aria-label="close"></button>
                                            </div>

                                            <div class="modal-body p-5 pt-0">
                                                <form id="rate-book-form">
                                                    <div class="input-group d-flex justify-content-center pb-1">
                                                        <ul class="stars" id="rate-book">
                                                            <?php for ($i = 0; $i < 5; ++$i) { ?>
                                                                <li class="star" value="<?=$i + 1?>" id="<?=$i + 1?>">
                                                                    <i class="fa-solid fa-star fa-2xl"></i>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <div class="mt-4 d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-primary" id="rate-book-modal-submit">Применить</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="card py-3 px-4">
                        <ul class="nav nav-underline mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="true">О книге</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-tab-pane" type="button" role="tab" aria-controls="content-tab-pane" aria-selected="false">Оглавление</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="false">Отзывы</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                                <p class="mb-3"><?=$book['annotation']?></p>
                                <h5 class="mb-3">Авторы</h5>
                                <div class="d-flex flex-row flex-wrap gap-3 mb-3">
                                    <?php foreach ($authors as $author): ?>
                                        <form action="/author.php" method="get">
                                            <button type="submit" name="author_id" value="<?=$author['author_id']?>" class="btn btn-outline-dark">
                                                <?=$author['author']?>
                                            </button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                                <h5 class="mb-3">Жанры</h5>
                                <div class="d-flex flex-row flex-wrap gap-3">
                                    <?php foreach ($genres as $genre): ?>
                                        <!-- <form action="/author.php" method="get"> -->
                                            <button type="submit" name="genre_id" value="<?=$genre['id']?>" class="btn btn-outline-dark">
                                                <?=$genre['name']?>
                                            </button>
                                        <!-- </form> -->
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="content-tab-pane" role="tabpanel" aria-labelledby="content-tab" tabindex="0">
                                <ul class="list-group list-group-flush">
                                <?php foreach ($chapters as $chapter): ?>
                                    <li class="list-group-item list-group-item-action px-0">
                                        <a href="/reader.php?book_id=<?=$id?>&chapter_number=<?=$chapter['chapter_number']?>" class="stretched-link link-body-emphasis text-decoration-none text-start m-0 p-0">
                                            Глава <?=$chapter['chapter_number']?>. <?=$chapter['name']?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                                <?php if ($login != null) { ?>
                                    <form id="send-review-form">
                                        <h5 class="mb-3">Новый отзыв</h5>
                                        <div class="mb-3">
                                            <textarea class="form-control" id="review-text" rows="3" required></textarea>
                                        </div>
                                        <div class="d-flex flex-row-reverse mb-0">
                                            <button type="submit" class="btn btn-primary" id="send-review-btn" value="<?=$id?>">Отправить</button>
                                        </div>
                                    </form>
                                <?php }; ?>
                                <ul class="list-group list-group-flush" id="book-reviews"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "book_handler.php"; ?>
    </body>
</html>