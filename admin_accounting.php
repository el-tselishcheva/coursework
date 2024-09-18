<?php
    include "header.php";
    $login = !empty($_SESSION['login']) ? $_SESSION['login'] : null;
    $user = !empty($login) ? selectUserByLogin($login) : null;
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Отчетность</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="card mb-4 py-3 px-4">
                <h2>Отчетность</h2>
                <ul class="nav nav-underline mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="books-tab" data-bs-toggle="tab" data-bs-target="#books-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="false">По книгам</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="authors-tab" data-bs-toggle="tab" data-bs-target="#authors-tab-pane" type="button" role="tab" aria-controls="lists-tab-pane" aria-selected="false">По авторам</button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="books-tab-pane" role="tabpanel" aria-labelledby="books-tab" tabindex="0">
                        <form id="generate-book-report-form" onsubmit="return false;">
                            <div class="row mb-3" novalidate>
                                <div class="col">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="generate-book-report-from-date" name="generate-book-report-from-date" required/>
                                        <label for="generate-book-report-from-date">C</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="generate-book-report-to-date" name="generate-book-report-to-date" required/>
                                        <label for="generate-book-report-to-date">По</label>
                                    </div>
                                </div>
                                <div class="col-auto align-self-center">
                                    <button class="btn btn-primary" id="generate-book-report-btn" onclick="generateBookReport();">Сформировать отчет</button>
                                </div>
                            </div>
                        </form>
                        <ul class="list-group list-group-flush" id="books-report"></ul>
                    </div>

                    <div class="tab-pane fade" id="authors-tab-pane" role="tabpanel" aria-labelledby="authors-tab" tabindex="0">
                    <form id="generate-author-report-form" onsubmit="return false;">
                            <div class="row mb-3" novalidate>
                                <div class="col">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="generate-author-report-from-date" name="generate-author-report-from-date" required/>
                                        <label for="generate-author-report-from-date">C</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="generate-author-report-to-date" name="generate-author-report-to-date" required/>
                                        <label for="generate-author-report-to-date">По</label>
                                    </div>
                                </div>
                                <div class="col-auto align-self-center">
                                    <button class="btn btn-primary" id="generate-author-report-btn" onclick="generateAuthorReport();">Сформировать отчет</button>
                                </div>
                            </div>
                        </form>
                        <ul class="list-group list-group-flush" id="authors-report"></ul>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {

            })

            function generateBookReport() {
                var from = $('#generate-book-report-from-date').val();
                var to = $('#generate-book-report-to-date').val();

                $.ajax({
                    url: 'ajax.php?action=generate_book_report',
                    method: 'post',
                    data: {
                        from_date: from,
                        to_date: to
                    },
                    success: function(data) {
                        $("#books-report").html(data);
                    }
                });
            }

            function generateAuthorReport() {
                var from = $('#generate-author-report-from-date').val();
                var to = $('#generate-author-report-to-date').val();

                $.ajax({
                    url: 'ajax.php?action=generate_author_report',
                    method: 'post',
                    data: {
                        from_date: from,
                        to_date: to
                    },
                    success: function(data) {
                        $("#authors-report").html(data);
                    }
                });
            }
        </script>
    </body>
</html>