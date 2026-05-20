(function () {
    var page = 2;
    var loading = false;
    var hasMore = true;
    var grid = document.getElementById('champion-grid');
    var trigger = document.getElementById('champion-load-trigger');

    if (!grid || !trigger || typeof championScroll === 'undefined') return;

    var ajaxUrl = championScroll.ajax_url;
    var nonce = championScroll.nonce;

    function loadMore() {
        if (loading || !hasMore) return;
        loading = true;

        var formData = new FormData();
        formData.append('action', 'load_more_champions');
        formData.append('page', page);
        formData.append('nonce', nonce);

        fetch(ajaxUrl, { method: 'POST', body: formData })
            .then(function (res) { return res.json(); })
            .then(function (response) {
                if (response.success && response.data.html) {
                    grid.insertAdjacentHTML('beforeend', response.data.html);
                    hasMore = response.data.has_more;
                    page++;
                } else {
                    hasMore = false;
                }
            })
            .catch(function () {
                hasMore = false;
            })
            .finally(function () {
                loading = false;
                if (!hasMore) {
                    observer.disconnect();
                }
            });
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                loadMore();
            }
        });
    }, { rootMargin: '200px' });

    observer.observe(trigger);
})();
