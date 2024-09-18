<script type="text/javascript">
    $(document).ready(function() {
        let check = false;
        updateBookRatingInfo();
        loadLists();
        loadReviews();

        function updateBookRatingInfo() {
            $.ajax({
                url: 'ajax.php?action=update_book_rating_info',
                method: 'POST',
                data: {
                    book_id: <?=$id?>
                },
                success: function(data) {
                    $("#book-rating-info").html(data);
                    showBookStars();
                }
            }).done(function(data) {<?=$id?>});
            return false;
        }

        function loadReviews() {
            $.ajax({
                url: 'ajax.php?action=update_reviews',
                type: 'POST',
                data: {book_id: <?=$id?>},
                success: function(data) {
                    $("#book-reviews").html(data);
                }
            });
        };

        function loadLists() {
            if ('<?=empty($user)?>' == '') {
                $.ajax({
                    url: 'ajax.php?action=load_lists',
                    method: 'POST',
                    data: {
                        book_id: <?=$id?>
                    },
                    success: function(data) {
                        $("#input-list").html(data);
                    }
                }).done(function(data) {<?=$id?>});
                return false;
            }
        }

        function hideCreateListModal() {
            $('#new-list-modal').modal('hide');
        }

        $("#rate-book-form").submit(function(e) {
            $.ajax({
                url: 'ajax.php?action=rate_book',
                method: 'POST',
                data: {
                    rating: rating_star,
                    book_id: <?=$id?>
                },
                success: function(data) {
                    updateBookRatingInfo();
                    $('#rate-book-modal').modal('hide');
                }
            })
            return false;
        })

        $('#create-new-list-form').submit(function(e) {
            return false;
        })

        $('#new-list-modal-submit').click(function(e) {
            if ($('#create-list-name').val() != '') {
                $.ajax({
                    url: 'ajax.php?action=add_to_new_list',
                    method: 'POST',
                    data: {
                        book_id: <?=$id?>,
                        list_name: $('#create-list-name').val()
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        // console.log(data);
                        if (data) {
                            $('#create-list-name').css('border-color', 'red');
                            $('#list-exists').show();
                        } else {
                            $('#create-new-list-form').trigger("reset");
                            $('#create-list-name').css('border-color', '#dee2e6');
                            $('#list-exists').hide();
                            $('#new-list-modal').modal('hide');
                            loadLists();
                            // alert('Книга добавлена в список!');
                        }
                    }
                })
            }
        })

        $('#yes-select-list').click(function(e) {
            $.ajax({
                url: 'ajax.php?action=add_to_list',
                method: 'POST',
                data: {
                    book_id: <?=$id?>,
                    list_id: $('#input-list').val()
                },
                success: function(data) {
                    $('#select-list-modal').modal('hide');
                }
            })
        })

        $("#new-list-modal-close").click(function(e) {
            $('#create-new-list-form').trigger("reset");
            $('#create-list-name').css('border-color', '#dee2e6');
            $('#list-exists').hide();
        })

        $('#send-review-form').submit(function(e) {
            var text = $("#review-text").val();
            var lines = text.split("\n");
            text = lines.join("<br>");

            $.post('ajax.php?action=send_review', {
                book_id: $('#send-review-btn').val(),
                review_text: text
            }).done(function(data) {$('#send-review-btn').val(), text});
            
            $('#send-review-form').trigger("reset");
            loadReviews();
            return false;
        });

        $('#start-reading-btn').click(function(e) {
            var chapters_number = <?=$chapters_number?>;
            if (chapters_number == 0) {
                alert("Не найдено глав для данной книги!");
                return false;
            };
        });

        let rating_star = 0;
        const rate_stars = document.querySelectorAll('#rate-book i');

        function showBookStars() {
            const book_stars = document.querySelectorAll('#book-stars i');
            let rating_star = $('#rating-star').val();
            book_stars.forEach((book_star, i) => {
                i < rating_star ? book_star.classList.add('selected') : book_star.classList.remove('selected');
            })
        }
    
        rate_stars.forEach((rate_star, i) => {
            rate_star.addEventListener('click', () => {
                document.addEventListener('click', (e) => {
                    let target = e.target;
                    let submit = document.querySelector('#rate-book-modal-submit');

                    do {
                        if (target == rate_star) {
                            rate_stars.forEach((rate_star, j) => {
                                i >= j ? rate_star.classList.add('selected') : rate_star.classList.remove('selected');
                            });
                            rating_star = target.parentNode.id;
                            return;
                        } else if (target == submit) {
                            return;
                        }
                        rating_star = 0;
                        target = target.parentNode;
                    } while (target);

                    rate_stars.forEach((rate_star) => {
                        rate_star.classList.remove('selected');
                    });
                })
            })
        });
    });
</script>