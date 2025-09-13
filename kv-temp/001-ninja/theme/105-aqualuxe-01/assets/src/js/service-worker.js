/**
 * AquaLuxe Service Worker
 *
 * Progressive Web App capabilities and advanced caching
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

const CACHE_NAME = 'aqualuxe-v1.0.0';
const CACHE_ASSETS = 'aqualuxe-assets-v1.0.0';
const CACHE_PAGES = 'aqualuxe-pages-v1.0.0';
const CACHE_IMAGES = 'aqualuxe-images-v1.0.0';

// Define what to cache
const urlsToCache = [
  '/',
  '/wp-content/themes/aqualuxe/assets/dist/css/app.css',
  '/wp-content/themes/aqualuxe/assets/dist/js/app.js',
  '/wp-content/themes/aqualuxe/screenshot.png',
];

// Install event - cache resources
self.addEventListener('install', function (event) {
  event.waitUntil(
    caches
      .open(CACHE_NAME)
      .then(function (cache) {
        // Cache opened successfully
        return cache.addAll(urlsToCache);
      })
      .then(function () {
        return self.skipWaiting();
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches
      .keys()
      .then(function (cacheNames) {
        return Promise.all(
          cacheNames.map(function (cacheName) {
            if (
              cacheName !== CACHE_NAME &&
              cacheName !== CACHE_ASSETS &&
              cacheName !== CACHE_PAGES &&
              cacheName !== CACHE_IMAGES
            ) {
              // Deleting old cache
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(function () {
        return self.clients.claim();
      })
  );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', function (event) {
  const requestUrl = new URL(event.request.url);

  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  // Skip admin and preview requests
  if (
    requestUrl.pathname.includes('/wp-admin/') ||
    requestUrl.pathname.includes('/wp-login.php') ||
    requestUrl.searchParams.has('preview')
  ) {
    return;
  }

  event.respondWith(
    caches.match(event.request).then(function (response) {
      // Return cached version if available
      if (response) {
        return response;
      }

      // Determine caching strategy based on request type
      if (
        requestUrl.pathname.endsWith('.css') ||
        requestUrl.pathname.endsWith('.js') ||
        requestUrl.pathname.includes('/assets/')
      ) {
        return handleAssetRequest(event.request);
      }

      if (requestUrl.pathname.match(/\.(jpg|jpeg|png|gif|svg|webp)$/)) {
        return handleImageRequest(event.request);
      }

      if (
        requestUrl.pathname === '/' ||
        requestUrl.pathname.includes('/page/') ||
        requestUrl.pathname.includes('/category/') ||
        requestUrl.pathname.includes('/tag/')
      ) {
        return handlePageRequest(event.request);
      }

      // Default: network first
      return fetch(event.request);
    })
  );
});

// Cache first strategy for assets
function handleAssetRequest(request) {
  return caches.open(CACHE_ASSETS).then(function (cache) {
    return cache.match(request).then(function (response) {
      if (response) {
        return response;
      }

      return fetch(request).then(function (networkResponse) {
        // Cache successful responses
        if (networkResponse.ok) {
          cache.put(request, networkResponse.clone());
        }
        return networkResponse;
      });
    });
  });
}

// Cache first strategy for images with fallback
function handleImageRequest(request) {
  return caches.open(CACHE_IMAGES).then(function (cache) {
    return cache.match(request).then(function (response) {
      if (response) {
        return response;
      }

      return fetch(request)
        .then(function (networkResponse) {
          if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
          }
          return networkResponse;
        })
        .catch(function () {
          // Return placeholder image if network fails
          return new Response(
            '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect width="400" height="300" fill="#f0f0f0"/><text x="200" y="150" text-anchor="middle" fill="#999">Image unavailable</text></svg>',
            { headers: { 'Content-Type': 'image/svg+xml' } }
          );
        });
    });
  });
}

// Stale while revalidate for pages
function handlePageRequest(request) {
  return caches.open(CACHE_PAGES).then(function (cache) {
    return cache.match(request).then(function (cachedResponse) {
      const fetchPromise = fetch(request).then(function (networkResponse) {
        if (networkResponse.ok) {
          cache.put(request, networkResponse.clone());
        }
        return networkResponse;
      });

      // Return cached version immediately if available, update in background
      return cachedResponse || fetchPromise;
    });
  });
}

// Background sync for form submissions
self.addEventListener('sync', function (event) {
  if (event.tag === 'contact-form') {
    event.waitUntil(syncContactForm());
  }
});

function syncContactForm() {
  // Handle offline form submissions
  return new Promise(function (resolve) {
    // Implementation would sync queued form data
    resolve();
  });
}

// Push notifications
self.addEventListener('push', function (event) {
  if (event.data) {
    const data = event.data.json();
    const options = {
      body: data.body,
      icon: '/wp-content/themes/aqualuxe/assets/dist/images/icon-192x192.png',
      badge: '/wp-content/themes/aqualuxe/assets/dist/images/badge-72x72.png',
      data: data.url,
    };

    event.waitUntil(self.registration.showNotification(data.title, options));
  }
});

// Handle notification clicks
self.addEventListener('notificationclick', function (event) {
  event.notification.close();

  if (event.notification.data) {
    event.waitUntil(clients.openWindow(event.notification.data));
  }
});

// Handle messages from main thread
self.addEventListener('message', function (event) {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }

  if (event.data && event.data.type === 'CACHE_URLS') {
    event.waitUntil(
      caches.open(CACHE_NAME).then(function (cache) {
        return cache.addAll(event.data.urls);
      })
    );
  }
});
