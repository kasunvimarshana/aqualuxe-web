/**
 * AquaLuxe Theme Service Worker
 *
 * This service worker provides offline capabilities and performance improvements.
 */

// Cache version - update when making changes to the service worker
const CACHE_VERSION = 'aqualuxe-v1.1.0';

// Cache names
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const PAGES_CACHE = `${CACHE_VERSION}-pages`;
const IMAGES_CACHE = `${CACHE_VERSION}-images`;
const FONTS_CACHE = `${CACHE_VERSION}-fonts`;
const API_CACHE = `${CACHE_VERSION}-api`;

// Resources to precache
const PRECACHE_RESOURCES = [
  '/',
  '/offline/',
  '/assets/css/main.css',
  '/assets/css/dark-mode.css',
  '/assets/js/app.js',
  '/assets/js/lazy-loading.js',
  '/assets/images/logo.svg',
  '/assets/images/logo-dark.svg',
  '/assets/images/offline.svg'
];

// Install event - precache static resources
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then((cache) => {
        return cache.addAll(PRECACHE_RESOURCES);
      })
      .then(() => {
        return self.skipWaiting();
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((cacheNames) => {
        return Promise.all(
          cacheNames
            .filter((cacheName) => {
              return cacheName.startsWith('aqualuxe-') && 
                     !cacheName.startsWith(CACHE_VERSION);
            })
            .map((cacheName) => {
              return caches.delete(cacheName);
            })
        );
      })
      .then(() => {
        return self.clients.claim();
      })
  );
});

// Fetch event - handle requests with appropriate caching strategies
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip cross-origin requests
  if (url.origin !== self.location.origin) {
    return;
  }

  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }

  // Handle API requests (network-first)
  if (url.pathname.startsWith('/wp-json/') || url.pathname.includes('/wp-admin/admin-ajax.php')) {
    event.respondWith(networkFirstStrategy(request));
    return;
  }

  // Handle page requests (network-first with offline fallback)
  if (request.mode === 'navigate') {
    event.respondWith(pageStrategy(request));
    return;
  }

  // Handle image requests (cache-first)
  if (request.destination === 'image') {
    event.respondWith(cacheFirstStrategy(request, IMAGES_CACHE));
    return;
  }

  // Handle font requests (cache-first)
  if (request.destination === 'font') {
    event.respondWith(cacheFirstStrategy(request, FONTS_CACHE));
    return;
  }

  // Handle CSS and JS requests (stale-while-revalidate)
  if (request.destination === 'style' || request.destination === 'script') {
    event.respondWith(staleWhileRevalidateStrategy(request));
    return;
  }

  // Default strategy (network-first)
  event.respondWith(networkFirstStrategy(request));
});

// Background sync event - handle offline form submissions
self.addEventListener('sync', (event) => {
  if (event.tag === 'form-submission') {
    event.waitUntil(syncFormSubmissions());
  }
});

// Push notification event
self.addEventListener('push', (event) => {
  if (!event.data) {
    return;
  }

  const data = event.data.json();
  
  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: data.icon || '/assets/images/logo.svg',
      badge: data.badge || '/assets/images/badge.png',
      data: data.data || {},
      actions: data.actions || []
    })
  );
});

// Notification click event
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  if (event.action) {
    // Handle action clicks
    handleNotificationAction(event.action, event.notification.data);
    return;
  }

  // Handle notification click
  event.waitUntil(
    clients.matchAll({ type: 'window' })
      .then((clientList) => {
        // If a window client is already open, focus it
        for (const client of clientList) {
          if (client.url === event.notification.data.url && 'focus' in client) {
            return client.focus();
          }
        }
        
        // Otherwise, open a new window
        if (clients.openWindow) {
          return clients.openWindow(event.notification.data.url || '/');
        }
      })
  );
});

/**
 * Network-first strategy
 * Try network first, fall back to cache
 *
 * @param {Request} request The request object
 * @returns {Promise<Response>} The response
 */
async function networkFirstStrategy(request) {
  const cache = await caches.open(PAGES_CACHE);
  
  try {
    const networkResponse = await fetch(request);
    
    // Cache successful responses
    if (networkResponse.ok) {
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // If no cached response, throw error
    throw error;
  }
}

/**
 * Cache-first strategy
 * Try cache first, fall back to network
 *
 * @param {Request} request The request object
 * @param {string} cacheName The cache name
 * @returns {Promise<Response>} The response
 */
async function cacheFirstStrategy(request, cacheName) {
  const cache = await caches.open(cacheName);
  const cachedResponse = await cache.match(request);
  
  if (cachedResponse) {
    return cachedResponse;
  }
  
  try {
    const networkResponse = await fetch(request);
    
    // Cache successful responses
    if (networkResponse.ok) {
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    // If no cached response and network fails, throw error
    throw error;
  }
}

/**
 * Stale-while-revalidate strategy
 * Return cached response immediately, then update cache in background
 *
 * @param {Request} request The request object
 * @returns {Promise<Response>} The response
 */
async function staleWhileRevalidateStrategy(request) {
  const cache = await caches.open(STATIC_CACHE);
  const cachedResponse = await cache.match(request);
  
  // Update cache in background
  const updateCache = fetch(request)
    .then((networkResponse) => {
      if (networkResponse.ok) {
        cache.put(request, networkResponse.clone());
      }
      return networkResponse;
    })
    .catch((error) => {
      console.error('Failed to update cache:', error);
    });
  
  // Return cached response if available, otherwise wait for network
  return cachedResponse || updateCache;
}

/**
 * Page strategy
 * Network-first with offline fallback for navigate requests
 *
 * @param {Request} request The request object
 * @returns {Promise<Response>} The response
 */
async function pageStrategy(request) {
  try {
    // Try network first
    const networkResponse = await fetch(request);
    
    // Cache successful responses
    if (networkResponse.ok) {
      const cache = await caches.open(PAGES_CACHE);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    // If network fails, try cache
    const cache = await caches.open(PAGES_CACHE);
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // If no cached response, return offline page
    return caches.match('/offline/');
  }
}

/**
 * Sync form submissions
 * Send cached form submissions when back online
 *
 * @returns {Promise<void>}
 */
async function syncFormSubmissions() {
  try {
    const cache = await caches.open('form-submissions');
    const requests = await cache.keys();
    
    await Promise.all(
      requests.map(async (request) => {
        const formData = await cache.match(request).then(res => res.json());
        
        try {
          // Attempt to submit the form
          const response = await fetch(request.url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
          });
          
          if (response.ok) {
            // If successful, remove from cache
            await cache.delete(request);
          }
        } catch (error) {
          console.error('Failed to sync form submission:', error);
        }
      })
    );
  } catch (error) {
    console.error('Failed to sync form submissions:', error);
  }
}

/**
 * Handle notification action
 *
 * @param {string} action The action ID
 * @param {Object} data The notification data
 * @returns {Promise<void>}
 */
async function handleNotificationAction(action, data) {
  switch (action) {
    case 'view-product':
      if (data.productUrl) {
        await clients.openWindow(data.productUrl);
      }
      break;
    
    case 'view-cart':
      await clients.openWindow('/cart/');
      break;
    
    case 'view-order':
      if (data.orderId) {
        await clients.openWindow(`/my-account/view-order/${data.orderId}/`);
      }
      break;
    
    default:
      // Default action
      await clients.openWindow('/');
  }
}