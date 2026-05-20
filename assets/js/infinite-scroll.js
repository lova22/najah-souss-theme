(function($) {
    var page = 2; // start from second page
    var loading = false;
    var hasMore = true;
    var $grid = $('.champion-grid'); // ensure this container selector matches the archive markup
    var ajaxUrl = championScroll.ajax_url;
    var nonce = championScroll.nonce;

    function loadMore() {
        if (loading || !hasMore) return;
        loading = true;
        $.post(ajaxUrl, {
            action: 'load_more_champions',
            page: page,
            nonce: nonce
        })
        .done(function(response) {
            if (response.success) {
                $grid.append(response.data.html);
                hasMore = response.data.has_more;
                page++;
            } else {
                hasMore = false;
            }
        })
        .always(function() {
            loading = false;
        });
    }

    $(window).on('scroll', function() {
        if (!hasMore) return;
        var scrollBottom = $(window).scrollTop() + $(window).height();
        var gridBottom = $grid.offset().top + $grid.outerHeight();
        if (scrollBottom > gridBottom - 200) { // trigger a bit before reaching bottom
            loadMore();
        }
    });
})(jQuery);
