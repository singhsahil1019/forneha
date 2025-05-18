jQuery(document).ready(function ($) {
    let currentSort = 'popular';
    let currentPage = 1;
    let perPage = parseInt($('#tc-submissions').data('per-page')) || 2;;

    function loadSubmissions(reset = false) {
        $.ajax({
            type: 'POST',
            url: tc_ajax.ajax_url,
            data: {
                action: 'load_tc_submissions',
                page: currentPage,
                sort: currentSort,
                per_page: perPage
            },
            beforeSend: function () {
                $('.photocontest-loaderWrap').show();
            },
            success: function (res) {
                if (reset) {
                    $('#tc-submissions').html(res.html);
                } else {
                    $('#tc-submissions').append(res.html);
                }

                if (!res.has_more) {
                    $('#tc-load-more').hide();
                } else {
                    $('#tc-load-more').show();
                }
            },
            complete: function () {
                $('.photocontest-loaderWrap').hide();
            }
        });
    }

    // Initial load
    loadSubmissions(true);

    $('#tc-filter').on('change', function () {
        currentSort = $(this).val();
        currentPage = 1;
        loadSubmissions(true);
    });

    $('#tc-load-more').on('click', function () {
        currentPage++;
        loadSubmissions();
    });

    // Open popup
    $(document).on('click', '.tc-submission-thumb img', function () {
        const postId = $(this).closest('.tc-submission-card').data('id');
        loadPopup(postId);
    });

    function loadPopup(postId) {
        $.ajax({
            type: 'POST',
             url: tc_ajax.ajax_url,
            data: {
                action: 'get_submission_popup',
                post_id: postId,
                sort: currentSort,
                per_page: perPage
            },
            beforeSend: function () {
                $('.photocontest-loaderWrap').show();
            },
            success: function (res) {
                if (res.success) {
                    $('.tc-popup-inner').html(res.data.html);
                    $('.tc-popup').fadeIn();
                }
            },
            complete: function () {
                $('.photocontest-loaderWrap').hide();
            }
        });
    }

    $(document).on('click', '.tc-popup-close', function () {
        $('.tc-popup').fadeOut();
    });

    $(document).on('click', '.tc-popup-prev, .tc-popup-next', function () {
        const newId = $(this).data('id');
        loadPopup(newId);
    });

    // Close popup when clicking outside the popup inner content
    $(document).on('click', '.tc-popup', function (e) {
        if (!$(e.target).closest('.tc-popup-inner').length && !$(e.target).hasClass('tc-popup-inner')) {
            $('.tc-popup').fadeOut();
        }
    });

});
