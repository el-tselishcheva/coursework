<?php
    require 'connect.php';

    function selectAll($table) {
        global $pdo;
        $sql = "SELECT * FROM $table
                ORDER BY id ASC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function selectAllById($table, $id) {
        global $pdo;
        $sql = "SELECT * FROM $table AS T
                WHERE T.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectBooksInfo() {
        global $pdo;
        $sql = "SELECT
                    B.image AS book_cover,
                    B.id AS book_id,
                    B.name AS book_name
                FROM books AS B";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function getNumberOfBookReviews($book_id) {
        global $pdo;
        $sql = "SELECT
                    COUNT(BR.id) AS total_number
                FROM reviews AS R
                WHERE R.book_id = $book_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectReviewsByBookId($id) {
        global $pdo;
        $sql = "SELECT
                    R.review_text,
                    R.posting_time,
                    R.user_id,
                    U.profile_picture,
                    U.name,
                    U.surname,
                    U.login
                FROM reviews AS R
                INNER JOIN users AS U on U.id = R.user_id
                WHERE R.book_id = $id
                ORDER BY R.posting_time DESC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query;
        // return $query->fetchAll();
    }

    function selectListsByUserId($id) {
        global $pdo;
        $sql = "SELECT
                    L.id,
                    L.name,
                    L.update_time,
                    COUNT(LB.book_id) AS total_number
                FROM lists AS L
                LEFT JOIN listed_books AS LB on LB.list_id = L.id
                WHERE L.user_id = $id
                GROUP BY L.id
                ORDER BY L.update_time DESC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query;
        // return $query->fetchAll();
    }

    function selectListInfoById($id) {
        global $pdo;
        $sql = "SELECT
                    L.id,
                    L.name AS list_name,
                    L.update_time,
                    L.user_id,
                    U.name,
                    U.surname
                FROM lists AS L
                INNER JOIN users AS U on U.id = L.user_id
                WHERE L.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectBooksByListId($id) {
        global $pdo;
        $sql = "SELECT
                    B.id AS book_id,
                    B.name AS book_name,
                    B.image AS book_cover
                FROM listed_books AS LB
                INNER JOIN books AS B on B.id = LB.book_id
                WHERE LB.list_id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function checkBookByListId($list_id, $book_id) {
        global $pdo;
        $sql = "SELECT check_book($list_id, $book_id)";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectFollowingsByUserId($id) {
        global $pdo;
        $sql = "SELECT * FROM authors AS A
                INNER JOIN following_authors AS F on F.author_id = A.id
                WHERE F.user_id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query;
        // return $query->fetchAll();
    }

    function getNumberOfUserFollowings($user_id) {
        global $pdo;
        $sql = "SELECT
                    COUNT(FA.author_id) AS total_number
                FROM following_authors AS FA
                WHERE FA.user_id = $user_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function getNumberOfAuthorFollowers($author_id) {
        global $pdo;
        $sql = "SELECT
                    COUNT(FA.user_id) AS total_number
                FROM following_authors AS FA
                WHERE FA.author_id = $author_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function checkIfFollowingAuthor($user_id, $author_id) {
        global $pdo;
        $sql = "SELECT * FROM following_authors AS FA
                WHERE FA.user_id = $user_id AND FA.author_id = $author_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function generateBookReport($from_date, $to_date) {
        global $pdo;
        $sql = "SELECT
                    B.id,
                    B.name,
                    B.image AS book_cover,
                    COUNT(DISTINCT CASE WHEN RT.rate_date >= '$from_date' AND RT.rate_date <= '$to_date' THEN
                        RT.id
                    ELSE NULL
                    END) AS rated_amount,
                    COUNT(DISTINCT CASE WHEN RW.posting_time >= '$from_date' AND RW.posting_time <= '$to_date' THEN
                        RW.id
                    ELSE NULL
                    END) AS reviewed_amount
                FROM books AS B
                LEFT JOIN ratings AS RT on RT.book_id = B.id
                LEFT JOIN reviews AS RW on RW.book_id = B.id
                GROUP BY B.id, B.name
                ORDER BY rated_amount DESC NULLS LAST, reviewed_amount";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function generateAuthorReport($from_date, $to_date) {
        global $pdo;
        $sql = "SELECT
        A.id,
        A.name,
        A.image AS author_pfp,
        COUNT(CASE WHEN FA.follow_date >= '$from_date' AND FA.follow_date <= '$to_date' THEN
                    FA.user_id
                ELSE NULL
                END) AS followers_amount
            FROM authors AS A
            LEFT JOIN following_authors AS FA on FA.author_id = A.id
            GROUP BY A.id, A.name
            ORDER BY followers_amount DESC NULLS LAST";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function followAuthor($user_id, $author_id, $follow_time) {
        global $pdo;
        $sql = "INSERT INTO following_authors (user_id, author_id, follow_date)
                VALUES ($user_id, $author_id, '$follow_time')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function deleteBookFromList($list_id, $book_id) {
        global $pdo;
        $sql = "DELETE FROM listed_books AS LB
                WHERE LB.list_id = $list_id AND LB.book_id = $book_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function unfollowAuthor($user_id, $author_id) {
        global $pdo;
        $sql = "DELETE FROM following_authors AS FA
                WHERE FA.user_id = $user_id AND FA.author_id = $author_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectBookReviewsByUserId($id) {
        global $pdo;
        $sql = "SELECT
                    R.review_text,
                    R.posting_time,
                    U.profile_picture,
                    U.name,
                    U.surname,
                    U.login,
                    R.book_id,
                    B.image,
                    B.name AS book_name
                FROM reviews AS R
                INNER JOIN users AS U on U.id = R.user_id
                INNER JOIN books AS B on B.id = R.book_id
                WHERE R.user_id = $id
                ORDER BY R.posting_time DESC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function checkBookRating($user_id, $book_id) {
        global $pdo;
        $sql = "SELECT check_book_rating($user_id, $book_id)";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function getAverageOfBookRatings($book_id) {
        global $pdo;
        $sql = "SELECT
                    AVG(R.rating)
                FROM ratings AS R
                WHERE R.book_id = $book_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function getNumberOfBookRatings($book_id) {
        global $pdo;
        $sql = "SELECT
                    COUNT(R.id)
                FROM ratings AS R
                WHERE R.book_id = $book_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function updateBookRating($user_id, $book_id, $rating, $rate_time) {
        global $pdo;
        $sql = "UPDATE ratings AS R
                SET rating = $rating, rate_date = '$rate_time'
                WHERE R.user_id = $user_id AND R.book_id = $book_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function insertNewBookRating($user_id, $book_id, $rating, $rate_time) {
        global $pdo;
        $sql = "INSERT INTO ratings (user_id, book_id, rating, rate_date)
                VALUES ($user_id, $book_id, $rating, '$rate_time')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function insertNewBookReview($user_id, $book_id, $review_text, $posting_time) {
        global $pdo;
        $sql = "INSERT INTO reviews (user_id, book_id, review_text, posting_time)
                VALUES ($user_id, $book_id, '$review_text', '$posting_time')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function deleteList($id) {
        global $pdo;
        $sql = "DELETE FROM lists AS L
                WHERE L.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function insertNewList($user_id, $name, $update_time) {
        global $pdo;
        $sql = "INSERT INTO lists (user_id, name, update_time)
                VALUES ($user_id, '$name', '$update_time')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectBooksByAuthorId($id) {
        global $pdo;
        $sql = "SELECT
                    B.image AS book_cover,
                    B.id AS book_id,
                    B.name AS book_name,
                    A.id AS author_id,
                    A.name AS author
                FROM books AS B
                INNER JOIN authors_and_books AS AaB on AaB.book_id = B.id
                INNER JOIN authors AS A on A.id = AaB.author_id
                WHERE A.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function selectChaptersByBookId($id) {
        global $pdo;
        $sql = "SELECT
                    C.id,
                    C.name,
                    C.sequence_number AS chapter_number
                FROM chapters AS C
                WHERE C.book_id = $id
                ORDER BY C.sequence_number ASC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function selectGenresByBookId($id) {
        global $pdo;
        $sql = "SELECT
                    G.id,
                    G.name
                FROM genres AS G
                INNER JOIN book_genres AS BG on BG.genre_id = G.id
                WHERE BG.book_id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function selectAuthorsByBookId($id) {
        global $pdo;
        $sql = "SELECT
                    A.id AS author_id,
                    A.name AS author
                FROM authors AS A
                INNER JOIN authors_and_books AS AaB on AaB.author_id = A.id
                INNER JOIN books AS B on B.id = AaB.book_id
                WHERE B.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function selectChapter($book_id, $chapter_number) {
        global $pdo;
        $sql = "SELECT
                    C.name AS name,
                    C.sequence_number AS number,
                    C.chapter_text AS text
                FROM chapters AS C
                WHERE C.book_id = $book_id AND C.sequence_number = $chapter_number";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function getNumberOfChapters($book_id) {
        global $pdo;
        // $sql = "SELECT
        //             COUNT(C.id) AS total_number
        //         FROM chapters AS C
        //         WHERE C.book_id = $book_id";
        $sql = "SELECT * FROM getNumberOfChapters($book_id)";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectAllGenders() {
        global $pdo;
        $sql = "SELECT * FROM genders";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetchAll();
    }

    function insertBookIntoList($list_id, $book_id) {
        global $pdo;
        $sql = "INSERT INTO listed_books (list_id, book_id)
                VALUES ($list_id, $book_id)";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function makeAdmin($id) {
        global $pdo;
        $sql = "UPDATE users AS U
                SET is_admin = true
                WHERE U.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function banUser($id) {
        global $pdo;
        $sql = "UPDATE users AS U
                SET is_banned = true
                WHERE U.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function unbanUser($id) {
        global $pdo;
        $sql = "UPDATE users AS U
                SET is_banned = false, login_attempt = 3
                WHERE U.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function editList($id, $name) {
        global $pdo;
        $sql = "UPDATE lists AS L
                SET name = '$name'
                WHERE L.id = $id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function updateList($list_id, $update_time) {
        global $pdo;
        $sql = "UPDATE lists AS L
                SET update_time = '$update_time'
                WHERE L.id = $list_id";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function checkLogin($login) {
        global $pdo;
        $sql = "SELECT check_login('$login')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function checkPassword($login, $password) {
        global $pdo;
        $sql = "SELECT check_password('$login', '$password')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectListByName($user_id, $list_name) {
        global $pdo;
        $sql = "SELECT * FROM lists AS L
                WHERE L.user_id = $user_id AND L.name = '$list_name'";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function checkList($id, $list) {
        global $pdo;
        $sql = "SELECT check_list($id, '$list')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function loginHandler($login, $password) {
        global $pdo;
        $sql = "SELECT login_handler('$login', '$password')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function insertNewUser($name, $surname, $gender_id, $birthdate, $login, $password) {
        global $pdo;
        // $sql = "INSERT INTO users (name, surname, gender_id, birthdate, login, password)
        //         VALUES ('$name', '$surname', $gender_id, '$birthdate', '$login', '$password')";
        $sql = "SELECT insertNewUser('$name', '$surname', $gender_id, '$birthdate', '$login', '$password')";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function updateUser($name, $surname, $gender_id, $birthdate, $login_old, $login_new, $password, $img) {
        $pass_str = '';
        $img_str = '';

        if ($password != null) {
            $pass_str = ", password = '$password'";
        }
        if ($img != null) {
            $img_str = ", profile_picture = '$img'";
        }

        global $pdo;
        // $sql = "UPDATE users AS U
        //         SET name = '$name', surname = '$surname', gender_id = $gender_id, birthdate = '$birthdate', login = '$login_new', password = '$password', profile_picture = '$img'
        //         WHERE U.login = '$login_old'";
        $sql = "UPDATE users AS U
                SET name = '$name', surname = '$surname', gender_id = $gender_id, birthdate = '$birthdate', login = '$login_new'$pass_str$img_str
                WHERE U.login = '$login_old'";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }

    function selectUserByLogin($login) {
        global $pdo;
        $sql = "SELECT * FROM users AS U
                WHERE U.login = '$login'";
        $query = $pdo->prepare($sql);
        $query->execute();
        $errInfo = $query->errorInfo();

        if ($errInfo[0] !== PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }

        return $query->fetch();
    }
?>