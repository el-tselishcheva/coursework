<?php
    include "header.php";
    
    $book_id = !empty($_GET['book_id']) ? $_GET['book_id'] : 'нет данных ;((';
    $chapter_number = !empty($_GET['chapter_number']) ? $_GET['chapter_number'] : 'нет данных ;((';

    $book = !empty($book_id) ? selectAllById('books', $book_id) : null;
    $authors = !empty($book_id) ? selectAuthorsByBookId($book_id) : null;
    $chapter = !empty($book_id) ? selectChapter($book_id, $chapter_number) : null;

    $reading_url = "/reader.php?book_id=" .$book_id. "&chapter_number=" .$chapter['number'];
    $read_next = "/reader_handler.php?book_id=" .$book_id. "&chapter_number=" .($chapter['number'] + 1);
    $read_previuos = "/reader_handler.php?book_id=" .$book_id. "&chapter_number=" .($chapter['number'] - 1);
?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Читать</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://kit.fontawesome.com/757dc033c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="card py-3 px-4 mb-4">
                <h2 class="m-0 p-0"><?=$book['name']?></h2>                    
                <?php foreach ($authors as $author): ?>
                    <a href="/author.php?author_id=<?=$author['author_id']?>" class="text-decoration-none text-start m-0 p-0">
                        <?=$author['author']?>
                    </a>
                <?php endforeach; ?>

                <h2 class="my-2 p-0"><?=$chapter['name']?></h2>
                <p class="mb-3"><?=$chapter['text']?></p>
                
                <div class="d-flex justify-content-between">
                    <a href="<?=$read_previuos?>">
                        <button type="submit" class="btn btn-primary">Назад</button>
                    </a>
                    <a href="<?=$read_next?>">
                        <button type="submit" class="btn btn-primary">Вперед</button>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>