<?php
    include "db.php";

    $book_id = !empty($_GET['book_id']) ? $_GET['book_id'] : null;
    $chapter_number = !empty($_GET['chapter_number']) ? $_GET['chapter_number'] : null;

    echo $chapter_number;

    if ($chapter_number == 0 || $chapter_number > getNumberOfChapters($book_id)['getnumberofchapters']) {
        $url = "book.php?book_id=" .$book_id;
    } else {
        $url = "reader.php?book_id=" .$book_id. "&chapter_number=" .$chapter_number;
    }

    header("Location: /" .$url);
?>