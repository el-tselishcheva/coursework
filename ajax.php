<?php
    include "db.php";
    date_default_timezone_set('Europe/Moscow');
    session_start();

    if ($_GET['action'] == 'update_reviews') {
        $book_id = !empty($_POST['book_id']) ? ($_POST['book_id']) : null;
        $reviews = selectReviewsByBookId($book_id);
        $posting_time = null;

        if (empty($reviews)) { ?>
            <p class="text-muted">Нет отзывов.</p>
        <?php }

        while ($review = $reviews->fetch(PDO::FETCH_ASSOC)) {
            $posting_time = date_format(date_create($review['posting_time']), 'M j, Y H:i:s'); ?>
            
            <li class="list-group-item px-0">
                <div class="row mb-3">
                    <div class="col-auto">
                        <img src="/img/<?=$review['profile_picture']?>" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
                    </div>
                    <div class="col">
                        <p class="mb-2">
                            <a href="/profile.php?user_id=<?=$review['user_id']?>" class="link-body-emphasis text-decoration-none text-start fw-medium m-0 p-0">
                                <?=$review['name']?> <?=$review['surname']?>
                            </a>
                        </p>
                        <p class="text-muted m-0"><?=$posting_time?></p>
                    </div>
                </div>
                <p><?=$review['review_text']?></p>
            </li>

        <?php };
    }
    else if ($_GET['action'] == 'generate_book_report') {
        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];
        $book_reports = generateBookReport($from_date, $to_date);

        foreach ($book_reports as $book_report): ?>
            <li class="list-group-item px-0">
                <div class="row mb-3">
                    <div class="col-auto">
                        <img src="/img/<?=$book_report['book_cover']?>" alt="" width="64" height="64" class="rounded object-fit-cover">
                    </div>
                    <div class="col">
                        <a href="/book.php?book_id=<?=$book_report['id']?>" class="link-body-emphasis text-decoration-none text-start fw-medium m-0 p-0">
                            <?=$book_report['name']?>
                        </a>
                    </div>
                    <div class="col">
                        <p><?=$book_report['rated_amount']?> оценили</p>
                    </div>
                    <div class="col">
                        <p>было оставлено <?=$book_report['reviewed_amount']?> отзывов</p>
                    </div>
                </div>
            </li>
        <?php endforeach;
    }
    else if ($_GET['action'] == 'generate_author_report') {
        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];
        $author_reports = generateAuthorReport($from_date, $to_date);

        foreach ($author_reports as $author_report): ?>
            <li class="list-group-item px-0">
                <div class="row mb-3">
                    <div class="col-auto">
                        <img src="/img/<?=$author_report['author_pfp']?>" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
                    </div>
                    <div class="col">
                        <a href="/author.php?author_id=<?=$author_report['id']?>" class="link-body-emphasis text-decoration-none text-start fw-medium m-0 p-0">
                            <?=$author_report['name']?>
                        </a>
                    </div>
                    <div class="col">
                        <p><?=$author_report['followers_amount']?> подписались</p>
                    </div>
                </div>
            </li>
        <?php endforeach;
    }
    else if ($_GET['action'] == 'update_book_rating_info') {
        $book_id = $_POST["book_id"];
        $rating_number = getNumberOfBookRatings($book_id)['count'];
        $rating_average = getAverageOfBookRatings($book_id)['avg'];
        $rating = !empty($rating_average) ? round($rating_average, 2) : 0;
        $rating_star = !empty($rating_average) ? round($rating_average) : 0; ?>

        <div class="d-flex flex-row">
            <ul class="stars pe-3" id="book-stars">
                <?php for ($i = 0; $i < 5; ++$i) { ?>
                    <li class="star" data-value="<?=$i + 1?>">
                        <i class="fa-solid fa-star"></i>
                    </li>
                <?php } ?>
            </ul>
            <p class="m-0 fw-bold" id="rating-bold"><?=$rating?></p>
            <input hidden id="rating-star" value="<?=$rating_star?>">
        </div>
        <p class="m-0 text-muted"><?=$rating_number?> оценили</p>

        <?php
    }
    else if ($_GET['action'] == 'rate_book') {
        $login = $_SESSION["login"];
        $user_id = selectUserByLogin($login)['id'];
        $book_id = $_POST["book_id"];
        $rating = $_POST["rating"];
        $rate_time = date('Y-m-d H:i:s');

        $check = checkBookRating($user_id, $book_id)['check_book_rating'];
        if ($check == '1') {
            updateBookRating($user_id, $book_id, $rating, $rate_time);
        } else {
            insertNewBookRating($user_id, $book_id, $rating, $rate_time);
        }
    }
    else if ($_GET['action'] == 'send_review') {
        $login = $_SESSION["login"];
        $user = selectUserByLogin($login);

        $user_id = $user['id'];
        $book_id = $_POST["book_id"];
        $review_text = $_POST["review_text"];
        $posting_time = date('Y-m-d H:i:s');

        insertNewBookReview($user_id, $book_id, $review_text, $posting_time);
    }
    else if ($_GET['action'] == 'image_preview' && is_array($_FILES)) {
        if (is_uploaded_file($_FILES['img']['tmp_name'])) {
            $img_name = time() ."_". $_FILES['img']['name'];
            $img_tmp_name = $_FILES['img']['tmp_name'];
            $img_path = $_SERVER['DOCUMENT_ROOT'] .'/img/'. $img_name;
            $img_upload = move_uploaded_file($img_tmp_name, $img_path);

            $img_path = 'img/'. $img_name; ?>
                <p hidden id="img-path"><?=$img_path?></p>
                <img src="<?=$img_path?>" alt="" class="pfp-img rounded-circle bg-body-secondary">
        <?php }
    }
    else if ($_GET['action'] == 'delete_images') {
        $files = $_POST["files"];
        foreach ($files as $file):
            unlink($file);
        endforeach;
    }
    else if ($_GET['action'] == 'create_list') {
        $login = $_SESSION["login"];
        $user = selectUserByLogin($login);

        $user_id = $user['id'];
        $name = !empty($_POST['name']) ? ($_POST['name']) : null;
        $update_time = date('Y-m-d H:i:s');

        insertNewList($user_id, $name, $update_time);
    }
    else if ($_GET['action'] == 'delete_list') {
        $id = !empty($_POST['id']) ? ($_POST['id']) : null;
        deleteList($id);
    }
    else if ($_GET['action'] == 'edit_list') {
        $login = $_SESSION["login"];
        $user_id = selectUserByLogin($login)['id'];
        $list_id = !empty($_POST['id']) ? ($_POST['id']) : null;
        $name = !empty($_POST['name']) ? ($_POST['name']) : null;

        $response = checkList($user_id, $name)['check_list'];
        if ($response != 1) {
            editList($list_id, $name);
        }
        echo json_encode($response);
    }
    else if ($_GET['action'] == 'unfollow') {
        $user_id = !empty($_POST['user_id']) ? ($_POST['user_id']) : null;
        $author_id = !empty($_POST['author_id']) ? ($_POST['author_id']) : null;
        unfollowAuthor($user_id, $author_id);
    }
    else if ($_GET['action'] == 'update_followings') {
        $login = $_SESSION["login"];
        $user = selectUserByLogin($login);
        $user_id = $_POST["user_id"];
        $followings = selectFollowingsByUserId($user_id);
        $following_number = getNumberOfUserFollowings($user_id)['total_number'];

        if ($user['id'] == $user_id) {
            if ($following_number == 0) { ?>
                <p class="text-muted">Нет подписок.</p>
            <?php }

            $i = 0;
            while ($following = $followings->fetch(PDO::FETCH_ASSOC)) { ?>

                <div class="px-3">
                    <div class="row mb-3">
                        <div class="col-auto ps-0">
                            <img src="/img/<?=$following['image']?>" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
                        </div>
                        <div class="col">
                            <form action="/author.php" method="get" class="mb-2">
                                <!-- <button type="submit" name="author_id" value="<?=$following['id']?>" class="btn btn-link text-decoration-none fw-medium m-0 p-0">
                                    <?=$following['name']?>
                                </button> -->
                                <a href="/author.php?author_id=<?=$following['id']?>" class="link-body-emphasis text-decoration-none text-start fw-medium m-0 p-0">
                                    <?=$following['name']?>
                                </a>
                                <p class="text-muted">автор</p>
                            </form>
                        </div>
                        <div class="col-auto pt-2 pe-0 d-flex align-items-start">
                            <div class="d-flex flex-row">
                                <button type="button" class="unfollow-modal btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#unfollow-modal-<?=$following['id']?>">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </div>
                        </div>

                        <div class="modal fade" id="unfollow-modal-<?=$following['id']?>" tabindex="-1" aria-labelledby="unfollow-modal-label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h2 class="fw-bold mb-0 fs-2">Отменить подписку?</h2>
                                        <button type="button" class="btn-close" id="unfollow-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body p-5 pt-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="unfollow-name" id="unfollow-name" placeholder="автор" value="<?=$following['name']?>" disabled readonly>
                                        </div>

                                        <div class="mt-4 d-flex justify-content-around">
                                            <button class="yes-unfollow btn btn-primary col-3" id="yes-unfollow-<?=$following['id']?>" onclick="unfollow(<?=$user_id?>, <?=$following['id']?>);">Да</button>
                                            <button class="no-unfollow btn btn-primary col-3" id="no-unfollow-<?=$following['id']?>" onclick="hideUnfollowModal(<?=$following['id']?>);">Нет</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($i + 1 != $following_number) { ?>
                    <hr>
                <?php ++$i;
                }
            }
        }
    }
    else if ($_GET['action'] == 'load_lists') {
        $login = $_SESSION["login"];
        $user_id = selectUserByLogin($login)['id'];
        $book_id = $_POST["book_id"];
        $lists = selectListsByUserId($user_id);

        foreach ($lists as $list): 
            $check = checkBookByListId($list['id'], $book_id)['check_book'];
            if ($check != '1') {?>
                <option value="<?=$list['id']?>"><?=$list['name']?></option>
        <?php }
        endforeach;
    }
    else if ($_GET['action'] == 'update_lists') {
        $login = $_SESSION["login"];
        $user = selectUserByLogin($login);

        $user_id = $_POST["user_id"];
        $lists = selectListsByUserId($user_id);
        $update_time = null;

        if ($user['id'] == $user_id) {
            while ($list = $lists->fetch(PDO::FETCH_ASSOC)) {
                $update_time = date_format(date_create($list['update_time']), 'M j, Y H:i:s'); ?>

                <hr>
                <div class="px-3">
                    <div class="row mb-3" id="list-container">
                        <div class="col-auto pt-3 d-flex align-items-start">
                            <span><i class="fa-solid fa-heart fa-2xl"></i></span>
                        </div>
                        <div class="col pe-4">
                            <form action="/list.php" method="get" class="mb-0">
                                <button type="submit" name="list_id" id="list-id" value="<?=$list['id']?>" class="btn btn-link text-start text-decoration-none fw-medium m-0 p-0">
                                    <?=$list['name']?>
                                </button>
                            </form>
                            <p class="text-muted mb-0"><?=$list['total_number']?> книг</p>
                        </div>
                        <div class="col-auto p-0">
                            <p class="text-muted text-end mb-0">Обновлено</p>
                            <p class="text-muted text-end mb-0"><?=$update_time?></p>
                        </div>
                        <div class="col-auto pt-2 ps-4 pe-0 d-flex align-items-start">
                            <div class="d-flex flex-row">
                                <button type="button" class="me-3 btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-list-modal-<?=$list['id']?>" onclick="editListModal(<?=$list['id']?>);">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="delete-list-modal btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#delete-list-modal-<?=$list['id']?>">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>

                        <div class="modal fade" id="edit-list-modal-<?=$list['id']?>" tabindex="-1" aria-labelledby="edit-list-modal-label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h2 class="fw-bold mb-0 fs-2">Переименовать список</h2>
                                        <button type="button" class="btn-close" id="edit-list-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body p-5 pt-0" >
                                        <form id="edit-list-form" onsubmit="editList(<?=$list['id']?>); return false;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="edit-list-name" id="edit-list-name-<?=$list['id']?>" placeholder="название списка" value="<?=$list['name']?>" required>
                                                <p hidden id="edit-list-name-actual-<?=$list['id']?>"><?=$list['name']?></p>
                                            </div>
                                            <div class="text-danger" id="list-exists-<?=$list['id']?>" style="display: none; padding-left: 12px;">
                                                Список с таким названием уже существует.
                                            </div>

                                            <div class="mt-4 d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary" id="edit-list-modal-submit">Применить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="delete-list-modal-<?=$list['id']?>" tabindex="-1" aria-labelledby="delete-list-modal-label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h2 class="fw-bold mb-0 fs-2">Удалить список?</h2>
                                        <button type="button" class="btn-close" id="delete-list-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body p-5 pt-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="delete-list-name" id="delete-list-name" placeholder="название списка" value="<?=$list['name']?>" disabled readonly>
                                        </div>

                                        <div class="mt-4 d-flex justify-content-around">
                                            <button class="yes-delete-list btn btn-primary col-3" id="yes-delete-list-<?=$list['id']?>" onclick="deleteList(<?=$list['id']?>);">Да</button>
                                            <button class="no-delete-list btn btn-primary col-3" id="no-delete-list-<?=$list['id']?>" onclick="hideDeleteListModal(<?=$list['id']?>);">Нет</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php }
        }
    }
    else if ($_GET['action'] == 'update_list_info') {
        $login = !empty($_SESSION["login"]) ? $_SESSION["login"] : null ;
        $curr_user_id = !empty($login) ? selectUserByLogin($login)['id'] : null;
        $list_id = $_POST['list_id'];

        $list = selectListInfoById($list_id);
        $books = selectBooksByListId($list_id);
        $update_time = date_format(date_create($list['update_time']), 'M j, Y H:i:s');
        ?>
        <div class="col-auto pt-1 d-flex align-items-start">
            <span class="pt-2"><i class="fa-solid fa-heart fa-2xl"></i></span>
        </div>
        <div class="col align-self-center">
            <h4 class="mb-1"><?=$list['list_name']?></h4>
            <form action="/profile.php" method="get" class="m-0">
                <a href="/profile.php?user_id=<?=$list['user_id']?>" class="text-decoration-none text-start m-0 p-0">
                    <?=$list['name']?> <?=$list['surname']?>
                </a>
            </form>
        </div>
        <div class="col-auto pt-1">
            <p class="text-muted text-end m-0">Обновлено</p>
            <p class="text-muted text-end m-0"><?=$update_time?></p>
        </div>
        <?php if ($curr_user_id == $list['user_id']) { ?>
            <div class="col-auto d-flex align-items-start pt-2">
                <div class="d-flex flex-row">
                    <button type="button" class="me-3 btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-list-modal" onclick="editListModal();">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="delete-list-modal btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#delete-list-modal">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <div class="modal fade" id="edit-list-modal" tabindex="-1" aria-labelledby="edit-list-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header p-5 pb-4 border-bottom-0">
                            <h2 class="fw-bold mb-0 fs-2">Переименовать список</h2>
                            <button type="button" class="btn-close" id="edit-list-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-5 pt-0" >
                            <form id="edit-list-form" onsubmit="editList(<?=$list['id']?>); return false;">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="edit-list-name" id="edit-list-name" placeholder="название списка" value="<?=$list['list_name']?>" required>
                                    <p hidden id="edit-list-name-actual"><?=$list['list_name']?></p>
                                </div>
                                <div class="text-danger" id="list-exists" style="display: none; padding-left: 12px;">
                                    Список с таким названием уже существует.
                                </div>

                                <div class="mt-4 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary" id="edit-list-modal-submit">Применить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete-list-modal" tabindex="-1" aria-labelledby="delete-list-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header p-5 pb-4 border-bottom-0">
                            <h2 class="fw-bold mb-0 fs-2">Удалить список?</h2>
                            <button type="button" class="btn-close" id="delete-list-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-5 pt-0">
                            <div class="input-group">
                                <input type="text" class="form-control" name="delete-list-name" id="delete-list-name" placeholder="название списка" value="<?=$list['list_name']?>" disabled readonly>
                            </div>

                            <div class="mt-4 d-flex justify-content-around">
                                <button class="yes-delete-list btn btn-primary col-3" id="yes-delete-list" onclick="deleteList(<?=$list['id']?>);">Да</button>
                                <button class="no-delete-list btn btn-primary col-3" id="no-delete-list" onclick="hideDeleteListModal();">Нет</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    }
    else if ($_GET['action'] == 'update_list_books') {
        $login = !empty($_SESSION["login"]) ? $_SESSION["login"] : null ;
        $curr_user_id = !empty($login) ? selectUserByLogin($login)['id'] : null;
        $list_id = $_POST['list_id'];

        $list = selectListInfoById($list_id);
        $books = selectBooksByListId($list_id);
        $update_time = date_format(date_create($list['update_time']), 'M j, Y H:i:s');

        foreach ($books as $book): ?>
            <div class="col-lg-2 col-sm-4 p-3">
                <div class="card h-100">
                    <div class="position-relative">
                        <?php if ($curr_user_id == $list['user_id']) { ?>
                            <div class="position-absolute top-0 end-0 pt-2 pe-2 ">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#delete-book-modal-<?=$book['book_id']?>">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </div>

                            <div class="modal fade" id="delete-book-modal-<?=$book['book_id']?>" tabindex="-1" aria-labelledby="delete-book-modal-label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content rounded-4 shadow">
                                        <div class="modal-header p-5 pb-4 border-bottom-0">
                                            <h2 class="fw-bold mb-0 fs-2">Удалить книгу из списка?</h2>
                                            <button type="button" class="btn-close" id="delete-book-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body p-5 pt-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="delete-book-name" id="delete-book-name" placeholder="название книги" value="<?=$book['book_name']?>" disabled readonly>
                                            </div>

                                            <div class="mt-4 d-flex justify-content-around">
                                                <button class="yes-delete-book btn btn-primary col-3" id="yes-delete-book-<?=$book['book_id']?>" onclick="deleteBook(<?=$book['book_id']?>);">Да</button>
                                                <button class="no-delete-book btn btn-primary col-3" id="no-delete-book-<?=$book['book_id']?>" onclick="hideDeleteBookModal(<?=$book['book_id']?>);">Нет</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <img src="img/<?=$book['book_cover']?>" alt="" class="card-img-top object-fit-contain">
                    </div>
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
        <?php endforeach;
    }
    else if ($_GET['action'] == 'delete_book_from_list') {
        $list_id = !empty($_POST['list_id']) ? ($_POST['list_id']) : null;
        $book_id = !empty($_POST['id']) ? ($_POST['id']) : null;
        $update_time = date('Y-m-d H:i:s');
        deleteBookFromList($list_id, $book_id);
        updateList($list_id, $update_time);
    }
    else if ($_GET['action'] == 'add_to_new_list') {
        $login = $_SESSION['login'];
        $user_id = selectUserByLogin($login)['id'];
        $book_id = !empty($_POST['book_id']) ? ($_POST['book_id']) : null;
        $list_name = !empty($_POST['list_name']) ? ($_POST['list_name']) : null;
        $update_time = date('Y-m-d H:i:s');

        $response = checkList($user_id, $list_name)['check_list'];
        if ($response != 1) {
            insertNewList($user_id, $list_name, $update_time);
            $list_id = selectListByName($user_id, $list_name)['id'];
            insertBookIntoList($list_id, $book_id);
        }
        echo json_encode($response);
    }
    else if ($_GET['action'] == 'add_to_list') {
        $login = $_SESSION["login"];
        $user_id = selectUserByLogin($login)['id'];
        $book_id = !empty($_POST['book_id']) ? ($_POST['book_id']) : null;
        $list_id = !empty($_POST['list_id']) ? ($_POST['list_id']) : null;
        $update_time = date('Y-m-d H:i:s');

        insertBookIntoList($list_id, $book_id);
        updateList($list_id, $update_time);
    }
    else if ($_GET['action'] == 'show_users') {
        $login = $_SESSION["login"];
        $user = selectUserByLogin($login);
        $user_list = selectAll('users');

        foreach ($user_list as $i=>$user_entry): 
            if ($user_entry['id'] != $user['id']) {?>
            <div class="px-3">
                <div class="row mb-3">
                    <div class="col-auto ps-0">
                        <img src="/img/<?=$user_entry['profile_picture']?>" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
                    </div>
                    <div class="col">
                        <form action="/profile.php" method="get">
                            <button type="submit" name="user_id" value="<?=$user_entry['id']?>" class="btn btn-link text-decoration-none fw-medium m-0 p-0">
                                <?=$user_entry['name']?> <?=$user_entry['surname']?>
                                <?php if ($user_entry['is_banned']) { ?>
                                    <span class="badge text-bg-danger">в бане</span>
                                <?php } ?>
                            </button>
                            <?php if ($user_entry['is_admin']) { ?>
                                <p class="text-muted">администратор</p>
                            <?php } else { ?>
                                <p class="text-muted">пользователь</p>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="col-auto pt-2 ps-4 pe-0 d-flex align-items-start">
                        <div class="d-flex flex-row">
                            <button type="button" class="me-3 btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#select-user-modal-<?=$user_entry['id']?>">
                                <i class="fa-solid fa-gear"></i>
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="select-user-modal-<?=$user_entry['id']?>" tabindex="-1" aria-labelledby="select-user-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h2 class="fw-bold mb-0 fs-2">Выберите действие</h2>
                                    <button type="button" class="btn-close" id="select-user-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body p-5 pt-0">
                                    <div class="d-flex flex-column align-items-center">
                                        <select class="form-select mb-4" id="input-option-<?=$user_entry['id']?>" name="option">
                                            <?php if (!$user_entry['is_admin']) { ?>
                                                <option value="#make-admin-user-modal-<?=$user_entry['id']?>">Назначить администратором</option>
                                            <?php } ?>
                                            <?php if (!$user_entry['is_banned']) { ?>
                                                <option value="#ban-user-modal-<?=$user_entry['id']?>">Заблокировать</option>
                                            <?php } else { ?>
                                                <option value="#unban-user-modal-<?=$user_entry['id']?>">Разблокировать</option>
                                            <?php } ?>
                                                <!-- <option value="#delete-user-modal-<?=$user_entry['id']?>">Удалить</option> -->
                                        </select>
                                        <button class="yes-select-user btn btn-primary col-3" id="yes-select-user-<?=$user_entry['id']?>" onclick="selectOption(<?=$user_entry['id']?>);">Выбрать</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="make-admin-user-modal-<?=$user_entry['id']?>" tabindex="-1" aria-labelledby="make-admin-user-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h2 class="fw-bold mb-0 fs-2">Выдать пользователю права администратора?</h2>
                                    <button type="button" class="btn-close" id="make-admin-user-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body p-5 pt-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="make-admin-user-name" id="make-admin-user-name" placeholder="логин" value="<?=$user_entry['login']?>" disabled readonly>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-around">
                                        <button class="yes-make-admin-user btn btn-primary col-3" id="yes-make-admin-user-<?=$user_entry['id']?>" onclick="makeAdmin(<?=$user_entry['id']?>);">Да</button>
                                        <button class="no-make-admin-user btn btn-primary col-3" id="no-make-admin-user-<?=$user_entry['id']?>" onclick="hideMakeAdminModal(<?=$user_entry['id']?>);">Нет</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ban-user-modal-<?=$user_entry['id']?>" tabindex="-1" aria-labelledby="ban-user-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h2 class="fw-bold mb-0 fs-2">Заблокировать пользователя?</h2>
                                    <button type="button" class="btn-close" id="ban-user-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body p-5 pt-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ban-user-name" id="ban-user-name" placeholder="логин" value="<?=$user_entry['login']?>" disabled readonly>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-around">
                                        <button class="yes-ban-user btn btn-primary col-3" id="yes-ban-user-<?=$user_entry['id']?>" onclick="banUser(<?=$user_entry['id']?>);">Да</button>
                                        <button class="no-ban-user btn btn-primary col-3" id="no-ban-user-<?=$user_entry['id']?>" onclick="hideBanUserModal(<?=$user_entry['id']?>);">Нет</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="unban-user-modal-<?=$user_entry['id']?>" tabindex="-1" aria-labelledby="unban-user-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h2 class="fw-bold mb-0 fs-2">Разблокировать пользователя?</h2>
                                    <button type="button" class="btn-close" id="unban-user-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body p-5 pt-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="unban-user-name" id="unban-user-name" placeholder="логин" value="<?=$user_entry['login']?>" disabled readonly>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-around">
                                        <button class="yes-unban-user btn btn-primary col-3" id="yes-unban-user-<?=$user_entry['id']?>" onclick="unbanUser(<?=$user_entry['id']?>);">Да</button>
                                        <button class="no-unban-user btn btn-primary col-3" id="no-unban-user-<?=$user_entry['id']?>" onclick="hideUnbanUserModal(<?=$user_entry['id']?>);">Нет</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="delete-user-modal-<?=$user_entry['id']?>" tabindex="-1" aria-labelledby="delete-user-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h2 class="fw-bold mb-0 fs-2">Удалить пользователя?</h2>
                                    <button type="button" class="btn-close" id="delete-user-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body p-5 pt-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="delete-user-name" id="delete-user-name" placeholder="логин" value="<?=$user_entry['login']?>" disabled readonly>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-around">
                                        <button class="yes-delete-user btn btn-primary col-3" id="yes-delete-user-<?=$user_entry['id']?>" onclick="deleteUser(<?=$user_entry['id']?>);">Да</button>
                                        <button class="no-delete-user btn btn-primary col-3" id="no-delete-user-<?=$user_entry['id']?>" onclick="hideDeleteUserModal(<?=$user_entry['id']?>);">Нет</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($i < count($user_list) - 1) { ?>
                <hr>
            <?php }
            }
        endforeach;
    }
    else if ($_GET['action'] == 'check_login') {
        $login = !empty($_POST['login']) ? ($_POST['login']) : null;
        $check = checkLogin($login)['check_login'];
        echo json_encode($check);
    }
    else if ($_GET['action'] == 'check_password') {
        $login = $_SESSION["login"];
        $password = !empty($_POST['password']) ? ($_POST['password']) : null;
        $check = checkPassword($login, $password)['check_password'];
        echo json_encode($check);
    }
    else if ($_GET['action'] == 'check_list') {
        $login = $_SESSION["login"];
        $id = selectUserByLogin($login)['id'];
        $list = !empty($_POST['list']) ? ($_POST['list']) : null;
        $check = checkList($id, $list)['check_list'];
        echo json_encode($check);
    }
    else if ($_GET['action'] == 'login_check') {
        $login = !empty($_POST['login']) ? ($_POST['login']) : null;
        $password = !empty($_POST['password']) ? ($_POST['password']) : null;

        $result['attempt'] = loginHandler($login, $password)['login_handler'];
        if (checkLogin($login)['check_login']) {
            $result['is_banned'] = selectUserByLogin($login)['is_banned'];
        } else {
            $result['is_banned'] = false;
        }
        echo json_encode($result);
    }
    else if ($_GET['action'] == 'make_admin') {
        $user_id = !empty($_POST['id']) ? ($_POST['id']) : null;
        makeAdmin($user_id);
    }
    else if ($_GET['action'] == 'ban_user') {
        $user_id = !empty($_POST['id']) ? ($_POST['id']) : null;
        banUser($user_id);
    }
    else if ($_GET['action'] == 'unban_user') {
        $user_id = !empty($_POST['id']) ? ($_POST['id']) : null;
        unbanUser($user_id);
    }
    else if ($_GET['action'] == 'show_books') {
        $book_list = selectAll('books');

        foreach ($book_list as $i=>$book_entry): 
            $author_list = !empty($book_entry['id']) ? selectAuthorsByBookId($book_entry['id']) : null; ?>
            <div class="px-3">
                <div class="row mb-3">
                    <div class="col-auto ps-0">
                        <img src="/img/<?=$book_entry['image']?>" alt="" width="64" height="64" class="rounded object-fit-cover">
                    </div>
                    <div class="col">
                        <form action="/book.php" method="get" class="mb-0">
                            <button type="submit" name="book_id" value="<?=$book_entry['id']?>" class="btn btn-link text-decoration-none fw-medium m-0 p-0">
                                <?=$book_entry['name']?>
                            </button>
                        </form>
                        <?php foreach ($author_list as $j=>$author_entry): ?>
                            <form action="/author.php" method="get" class="mb-0">
                                <button type="submit" name="author_id" value="<?=$author_entry['author_id']?>" class="btn btn-link text-decoration-none text-muted m-0 p-0">
                                <?=$author_entry['author']?>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php if ($i < count($book_list) - 1) { ?>
                <hr>
            <?php }
        endforeach;
    }
    else if ($_GET['action'] == 'show_authors') {
        $author_list = selectAll('authors');

        foreach ($author_list as $i=>$author_entry): 
        $followers_number = getNumberOfAuthorFollowers($author_entry['id'])['total_number']; ?>
            <div class="px-3">
                <div class="row mb-3">
                    <div class="col-auto ps-0">
                        <img src="/img/<?=$author_entry['image']?>" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
                    </div>
                    <div class="col">
                        <form action="/author.php" method="get">
                            <button type="submit" name="author_id" value="<?=$author_entry['id']?>" class="btn btn-link text-decoration-none fw-medium m-0 p-0">
                                <?=$author_entry['name']?>
                            </button>
                            <p class="text-muted"><?=$followers_number?> подписчиков</p>
                        </form>
                    </div>
                    
                </div>
            </div>
            <?php if ($i < count($author_list) - 1) { ?>
                <hr>
            <?php }
        endforeach;
    }
    else if ($_GET['action'] == 'show_follow_author_button') {
        $user_id = !empty($_POST['user_id']) ? ($_POST['user_id']) : null;
        $author_id = !empty($_POST['author_id']) ? ($_POST['author_id']) : null;

        if (!is_array(checkIfFollowingAuthor($user_id, $author_id))) {
        ?>
            <button class="btn btn-primary" id="start-following" onclick="clickFollow();">Подписаться</button>
            <button class="btn btn-secondary" id="stop-following" style="display: none" onclick="clickUnfollow();">Отписаться</button>
        <?php
        } else {
        ?>
            <button class="btn btn-primary" id="start-following" style="display: none" onclick="clickFollow();">Подписаться</button>
            <button class="btn btn-secondary" id="stop-following" onclick="clickUnfollow();">Отписаться</button>
        <?php
        }
    }
    else if ($_GET['action'] == 'show_author_followers') {
        $author_id = !empty($_POST['author_id']) ? ($_POST['author_id']) : null;
        $followers_number = getNumberOfAuthorFollowers($author_id)['total_number'];
        ?>
            <p><?=$followers_number?> подписчиков</p>
        <?php
    }
    else if ($_GET['action'] == 'start_following') {
        $user_id = !empty($_POST['user_id']) ? ($_POST['user_id']) : null;
        $author_id = !empty($_POST['author_id']) ? ($_POST['author_id']) : null;
        $follow_time = date('Y-m-d H:i:s');
        followAuthor($user_id, $author_id, $follow_time);
    }
    else if ($_GET['action'] == 'stop_following') {
        $user_id = !empty($_POST['user_id']) ? ($_POST['user_id']) : null;
        $author_id = !empty($_POST['author_id']) ? ($_POST['author_id']) : null;
        unfollowAuthor($user_id, $author_id);
    }
?>