/**
 * AquaLuxe Theme Service Worker Registration
 *
 * This script registers the service worker for offline capabilities and performance improvements.
 */

(function() {
  'use strict';

  // Check if service workers are supported
  if ('serviceWorker' in navigator) {
    // Wait for the page to load
    window.addEventListener('load', function() {
      // Register the service worker
      navigator.serviceWorker.register('/service-worker.js')
        .then(function(registration) {
          // Registration was successful
          console.log('ServiceWorker registration successful with scope: ', registration.scope);
          
          // Check for updates
          registration.addEventListener('updatefound', function() {
            // A new service worker is being installed
            const installingWorker = registration.installing;
            
            installingWorker.addEventListener('statechange', function() {
              if (installingWorker.state === 'installed') {
                if (navigator.serviceWorker.controller) {
                  // At this point, the old content will have been purged and
                  // the fresh content will have been added to the cache.
                  console.log('New content is available; please refresh.');
                  
                  // Show update notification
                  showUpdateNotification();
                } else {
                  // At this point, everything has been precached.
                  console.log('Content is cached for offline use.');
                }
              }
            });
          });
        })
        .catch(function(error) {
          // Registration failed
          console.error('ServiceWorker registration failed: ', error);
        });
      
      // Handle controller change (when a new service worker takes over)
      navigator.serviceWorker.addEventListener('controllerchange', function() {
        console.log('Controller changed, reloading...');
      });
    });
    
    // Handle offline form submissions
    document.addEventListener('submit', function(event) {
      // Check if the form has the data-offline attribute
      if (event.target.hasAttribute('data-offline')) {
        event.preventDefault();
        
        // Get form data
        const formData = new FormData(event.target);
        const formObject = {};
        
        formData.forEach(function(value, key) {
          formObject[key] = value;
        });
        
        // Store form data for later submission
        storeFormSubmission(event.target.action, formObject)
          .then(function() {
            // Show success message
            showOfflineFormMessage(event.target);
          })
          .catch(function(error) {
            console.error('Failed to store form submission:', error);
          });
      }
    });
  }
  
  /**
   * Store form submission for later sync
   *
   * @param {string} url The form action URL
   * @param {Object} formData The form data
   * @returns {Promise<void>}
   */
  async function storeFormSubmission(url, formData) {
    // Create a unique ID for this submission
    const submissionId = Date.now().toString();
    
    // Open the form submissions cache
    const cache = await caches.open('form-submissions');
    
    // Create a new request object
    const request = new Request(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    });
    
    // Store the form data
    await cache.put(request, new Response(JSON.stringify(formData)));
    
    // Register a sync event
    if ('sync' in navigator.serviceWorker) {
      const registration = await navigator.serviceWorker.ready;
      await registration.sync.register('form-submission');
    }
  }
  
  /**
   * Show offline form message
   *
   * @param {HTMLFormElement} form The form element
   */
  function showOfflineFormMessage(form) {
    // Create message element
    const message = document.createElement('div');
    message.className = 'offline-message';
    message.innerHTML = `
      <p>You're currently offline. Your form has been saved and will be submitted automatically when you're back online.</p>
      <button type="button" class="close-message">OK</button>
    `;
    
    // Add message after the form
    form.parentNode.insertBefore(message, form.nextSibling);
    
    // Add event listener to close button
    message.querySelector('.close-message').addEventListener('click', function() {
      message.remove();
    });
    
    // Reset the form
    form.reset();
  }
  
  /**
   * Show update notification
   */
  function showUpdateNotification() {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'update-notification';
    notification.innerHTML = `
      <p>A new version of this site is available. <a href="javascript:void(0)" class="refresh-page">Refresh</a> to update.</p>
      <button type="button" class="close-notification">×</button>
    `;
    
    // Add notification to the page
    document.body.appendChild(notification);
    
    // Add event listener to refresh link
    notification.querySelector('.refresh-page').addEventListener('click', function() {
      window.location.reload();
    });
    
    // Add event listener to close button
    notification.querySelector('.close-notification').addEventListener('click', function() {
      notification.remove();
    });
  }
})();