<?php
    // include "db.php";
    include "header.php";
    $books = selectBooksInfo();
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MyLib</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <h2>Книги</h2>
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
    </body>
</html>