/**
 * AquaLuxe Theme - Service Worker
 * 
 * This service worker provides offline capabilities and performance improvements through caching.
 */

// Cache version - update this when files change to invalidate old caches
const CACHE_VERSION = 'v1.0.0';

// Cache names
const STATIC_CACHE_NAME = `aqualuxe-static-${CACHE_VERSION}`;
const ASSETS_CACHE_NAME = `aqualuxe-assets-${CACHE_VERSION}`;
const PAGES_CACHE_NAME = `aqualuxe-pages-${CACHE_VERSION}`;
const API_CACHE_NAME = `aqualuxe-api-${CACHE_VERSION}`;

// Resources to cache immediately (App Shell)
const APP_SHELL = [
  '/',
  '/index.php',
  '/wp-content/themes/aqualuxe/style.css',
  '/wp-content/themes/aqualuxe/assets/dist/css/main.min.css',
  '/wp-content/themes/aqualuxe/assets/dist/js/main.min.js',
  '/wp-content/themes/aqualuxe/assets/dist/images/logo.svg',
  '/wp-content/themes/aqualuxe/assets/dist/images/logo-dark.svg',
  '/wp-content/themes/aqualuxe/assets/dist/images/favicon.ico',
  '/wp-content/themes/aqualuxe/assets/dist/fonts/inter-var.woff2',
  '/offline/'
];

// Assets to cache (images, fonts, etc.)
const ASSET_EXTENSIONS = [
  '.png', '.jpg', '.jpeg', '.svg', '.gif', '.webp',
  '.woff', '.woff2', '.ttf', '.eot',
  '.css', '.js'
];

// API endpoints to cache with network-first strategy
const API_ROUTES = [
  '/wp-json/wp/v2/',
  '/wp-json/wc/v3/'
];

// Install event - cache app shell
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(STATIC_CACHE_NAME)
      .then(cache => {
        console.log('Caching app shell');
        return cache.addAll(APP_SHELL);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames
            .filter(cacheName => {
              return cacheName.startsWith('aqualuxe-') && 
                     !cacheName.endsWith(CACHE_VERSION);
            })
            .map(cacheName => {
              console.log('Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            })
        );
      })
      .then(() => self.clients.claim())
  );
});

// Fetch event - handle requests with appropriate strategies
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);
  
  // Skip non-GET requests and browser extensions
  if (event.request.method !== 'GET' || 
      url.protocol !== 'http:' && url.protocol !== 'https:') {
    return;
  }
  
  // Skip WP admin and login pages
  if (url.pathname.startsWith('/wp-admin') || 
      url.pathname.startsWith('/wp-login')) {
    return;
  }
  
  // Handle API requests (network-first)
  if (API_ROUTES.some(route => url.pathname.includes(route))) {
    event.respondWith(networkFirstStrategy(event.request, API_CACHE_NAME));
    return;
  }
  
  // Handle asset requests (cache-first)
  if (ASSET_EXTENSIONS.some(ext => url.pathname.endsWith(ext))) {
    event.respondWith(cacheFirstStrategy(event.request, ASSETS_CACHE_NAME));
    return;
  }
  
  // Handle page requests (network-first with offline fallback)
  event.respondWith(pageStrategy(event.request));
});

/**
 * Cache-first strategy - try cache first, then network
 * @param {Request} request - The request to handle
 * @param {string} cacheName - The cache to use
 * @return {Promise<Response>} The response
 */
function cacheFirstStrategy(request, cacheName) {
  return caches.match(request)
    .then(response => {
      // Return cached response if found
      if (response) {
        return response;
      }
      
      // Otherwise fetch from network
      return fetch(request)
        .then(networkResponse => {
          // Cache the network response
          if (networkResponse.ok) {
            const clonedResponse = networkResponse.clone();
            caches.open(cacheName)
              .then(cache => cache.put(request, clonedResponse));
          }
          
          return networkResponse;
        })
        .catch(error => {
          console.error('Fetch failed:', error);
          // If it's an image, return a fallback
          if (request.destination === 'image') {
            return caches.match('/wp-content/themes/aqualuxe/assets/dist/images/placeholder.png');
          }
          
          throw error;
        });
    });
}

/**
 * Network-first strategy - try network first, then cache
 * @param {Request} request - The request to handle
 * @param {string} cacheName - The cache to use
 * @return {Promise<Response>} The response
 */
function networkFirstStrategy(request, cacheName) {
  return fetch(request)
    .then(response => {
      // Cache the response if successful
      if (response.ok) {
        const clonedResponse = response.clone();
        caches.open(cacheName)
          .then(cache => cache.put(request, clonedResponse));
      }
      
      return response;
    })
    .catch(() => {
      // If network fails, try the cache
      return caches.match(request);
    });
}

/**
 * Page strategy - network-first with offline fallback
 * @param {Request} request - The request to handle
 * @return {Promise<Response>} The response
 */
function pageStrategy(request) {
  return fetch(request)
    .then(response => {
      // Cache successful responses
      if (response.ok) {
        const clonedResponse = response.clone();
        caches.open(PAGES_CACHE_NAME)
          .then(cache => cache.put(request, clonedResponse));
      }
      
      return response;
    })
    .catch(() => {
      // If network fails, try the cache
      return caches.match(request)
        .then(cachedResponse => {
          // Return cached response if found
          if (cachedResponse) {
            return cachedResponse;
          }
          
          // Otherwise return offline page
          return caches.match('/offline/');
        });
    });
}

// Listen for skipWaiting message from client
self.addEventListener('message', event => {
  if (event.data && event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
});