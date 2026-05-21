const CACHE_NAME = 'ansae-pwa-cache-v1';
const OFFLINE_URL = '/wp-content/themes/najah-souss-theme/offline.php';

const SHELL_ASSETS = [
    OFFLINE_URL,
    '/wp-content/themes/najah-souss-theme/style.css',
    '/wp-content/themes/najah-souss-theme/manifest.json'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(SHELL_ASSETS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(name => name !== CACHE_NAME)
                          .map(name => caches.delete(name))
            );
        }).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    // Navigation requests (HTML pages) -> Network First, fallback to offline
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // Static assets -> Cache First, fallback to network
    event.respondWith(
        caches.match(event.request)
            .then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(event.request).then(response => {
                    // Optionally cache newly fetched dynamic assets here
                    return response;
                });
            })
    );
});
