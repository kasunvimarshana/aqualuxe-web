/**
 * AquaLuxe Theme - Service Worker Registration
 *
 * This file handles the registration of the service worker for offline capabilities.
 */

(function() {
  'use strict';
  
  /**
   * Register service worker
   */
  function registerServiceWorker() {
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js')
          .then(function(registration) {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
            
            // Check for updates
            registration.addEventListener('updatefound', function() {
              // A new service worker is installing
              const installingWorker = registration.installing;
              
              installingWorker.addEventListener('statechange', function() {
                if (installingWorker.state === 'installed') {
                  if (navigator.serviceWorker.controller) {
                    // New content is available, show update notification
                    showUpdateNotification();
                  } else {
                    // Content is cached for offline use
                    console.log('Content is cached for offline use.');
                  }
                }
              });
            });
          })
          .catch(function(error) {
            console.error('ServiceWorker registration failed: ', error);
          });
        
        // Handle service worker updates
        navigator.serviceWorker.addEventListener('controllerchange', function() {
          // The service worker has been updated and activated
          if (refreshing) return;
          
          // Reload the page to use the new service worker
          window.location.reload();
          refreshing = true;
        });
      });
    }
  }
  
  // Flag to prevent multiple refreshes
  let refreshing = false;
  
  /**
   * Show update notification
   */
  function showUpdateNotification() {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'update-notification';
    notification.innerHTML = `
      <div class="update-notification-content">
        <p>A new version of this site is available.</p>
        <button class="update-button">Update Now</button>
        <button class="dismiss-button">Dismiss</button>
      </div>
    `;
    
    // Add notification to DOM
    document.body.appendChild(notification);
    
    // Show notification with animation
    setTimeout(function() {
      notification.classList.add('is-active');
    }, 100);
    
    // Handle update button click
    const updateButton = notification.querySelector('.update-button');
    if (updateButton) {
      updateButton.addEventListener('click', function() {
        // Skip waiting and reload the page
        if (navigator.serviceWorker.controller) {
          navigator.serviceWorker.controller.postMessage({ action: 'skipWaiting' });
        }
      });
    }
    
    // Handle dismiss button click
    const dismissButton = notification.querySelector('.dismiss-button');
    if (dismissButton) {
      dismissButton.addEventListener('click', function() {
        notification.classList.remove('is-active');
        
        // Remove notification after animation
        setTimeout(function() {
          notification.remove();
        }, 300);
      });
    }
  }
  
  // Register service worker
  registerServiceWorker();
})();