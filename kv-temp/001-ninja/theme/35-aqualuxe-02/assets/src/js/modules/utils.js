/**
 * AquaLuxe WordPress Theme
 * Utility Functions Module
 */

/**
 * Utility functions for the AquaLuxe theme.
 */
(function() {
  /**
   * Debounce function
   * @param {Function} func - Function to debounce
   * @param {number} wait - Wait time in milliseconds
   * @param {boolean} immediate - Whether to execute immediately
   * @return {Function} - Debounced function
   */
  function debounce(func, wait, immediate) {
    let timeout;
    return function() {
      const context = this;
      const args = arguments;
      const later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      const callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }

  /**
   * Throttle function
   * @param {Function} func - Function to throttle
   * @param {number} limit - Limit in milliseconds
   * @return {Function} - Throttled function
   */
  function throttle(func, limit) {
    let inThrottle;
    return function() {
      const args = arguments;
      const context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }

  /**
   * Get viewport dimensions
   * @return {Object} - Viewport width and height
   */
  function getViewportDimensions() {
    return {
      width: window.innerWidth || document.documentElement.clientWidth,
      height: window.innerHeight || document.documentElement.clientHeight
    };
  }

  /**
   * Check if element is in viewport
   * @param {HTMLElement} element - Element to check
   * @param {number} offset - Offset in pixels
   * @return {boolean} - Whether element is in viewport
   */
  function isInViewport(element, offset = 0) {
    const rect = element.getBoundingClientRect();
    const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
    const viewportWidth = window.innerWidth || document.documentElement.clientWidth;
    
    return (
      rect.top >= 0 - offset &&
      rect.left >= 0 - offset &&
      rect.bottom <= viewportHeight + offset &&
      rect.right <= viewportWidth + offset
    );
  }

  /**
   * Get element offset from document top
   * @param {HTMLElement} element - Element to check
   * @return {number} - Offset from document top
   */
  function getOffsetTop(element) {
    let offsetTop = 0;
    while (element) {
      offsetTop += element.offsetTop;
      element = element.offsetParent;
    }
    return offsetTop;
  }

  /**
   * Smooth scroll to element
   * @param {HTMLElement|string} target - Target element or selector
   * @param {number} duration - Duration in milliseconds
   * @param {number} offset - Offset in pixels
   */
  function smoothScrollTo(target, duration = 500, offset = 0) {
    if (typeof target === 'string') {
      target = document.querySelector(target);
    }
    
    if (!target) {
      return;
    }
    
    const targetPosition = getOffsetTop(target) - offset;
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;
    
    function animation(currentTime) {
      if (startTime === null) {
        startTime = currentTime;
      }
      
      const timeElapsed = currentTime - startTime;
      const run = ease(timeElapsed, startPosition, distance, duration);
      
      window.scrollTo(0, run);
      
      if (timeElapsed < duration) {
        requestAnimationFrame(animation);
      }
    }
    
    function ease(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return c / 2 * t * t + b;
      t--;
      return -c / 2 * (t * (t - 2) - 1) + b;
    }
    
    requestAnimationFrame(animation);
  }

  /**
   * Add class to element when scrolled to
   * @param {HTMLElement|string} element - Element or selector
   * @param {string} className - Class name to add
   * @param {number} offset - Offset in pixels
   */
  function addClassOnScroll(element, className, offset = 0) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    
    if (!element) {
      return;
    }
    
    function checkScroll() {
      if (isInViewport(element, offset)) {
        element.classList.add(className);
        window.removeEventListener('scroll', scrollHandler);
      }
    }
    
    const scrollHandler = throttle(checkScroll, 100);
    window.addEventListener('scroll', scrollHandler);
    
    // Check on load
    checkScroll();
  }

  /**
   * Format number with commas
   * @param {number} number - Number to format
   * @return {string} - Formatted number
   */
  function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  /**
   * Format currency
   * @param {number} amount - Amount to format
   * @param {string} currencyCode - Currency code
   * @return {string} - Formatted currency
   */
  function formatCurrency(amount, currencyCode = 'USD') {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: currencyCode
    }).format(amount);
  }

  /**
   * Format date
   * @param {Date|string} date - Date to format
   * @param {Object} options - Intl.DateTimeFormat options
   * @return {string} - Formatted date
   */
  function formatDate(date, options = {}) {
    if (typeof date === 'string') {
      date = new Date(date);
    }
    
    const defaultOptions = {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
    
    const mergedOptions = { ...defaultOptions, ...options };
    
    return new Intl.DateTimeFormat('en-US', mergedOptions).format(date);
  }

  /**
   * Get URL parameters
   * @param {string} url - URL to parse
   * @return {Object} - URL parameters
   */
  function getUrlParameters(url = window.location.href) {
    const params = {};
    const parser = document.createElement('a');
    parser.href = url;
    
    const query = parser.search.substring(1);
    const vars = query.split('&');
    
    for (let i = 0; i < vars.length; i++) {
      const pair = vars[i].split('=');
      params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
    }
    
    return params;
  }

  /**
   * Set cookie
   * @param {string} name - Cookie name
   * @param {string} value - Cookie value
   * @param {number} days - Days until expiration
   */
  function setCookie(name, value, days) {
    let expires = '';
    
    if (days) {
      const date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = '; expires=' + date.toUTCString();
    }
    
    document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/';
  }

  /**
   * Get cookie
   * @param {string} name - Cookie name
   * @return {string|null} - Cookie value
   */
  function getCookie(name) {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') {
        c = c.substring(1, c.length);
      }
      
      if (c.indexOf(nameEQ) === 0) {
        return decodeURIComponent(c.substring(nameEQ.length, c.length));
      }
    }
    
    return null;
  }

  /**
   * Delete cookie
   * @param {string} name - Cookie name
   */
  function deleteCookie(name) {
    setCookie(name, '', -1);
  }

  /**
   * Show notification
   * @param {string} message - Notification message
   * @param {string} type - Notification type (success, error, warning, info)
   * @param {number} duration - Duration in milliseconds
   */
  function showNotification(message, type = 'info', duration = 3000) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'aqualuxe-notification notification-' + type;
    notification.textContent = message;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
      notification.classList.add('is-active');
    }, 10);
    
    // Hide and remove notification
    setTimeout(() => {
      notification.classList.remove('is-active');
      
      // Remove from DOM after animation
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 300);
    }, duration);
  }

  /**
   * Lazy load images
   */
  function lazyLoadImages() {
    const lazyImages = document.querySelectorAll('img.lazy');
    
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const image = entry.target;
            image.src = image.dataset.src;
            
            if (image.dataset.srcset) {
              image.srcset = image.dataset.srcset;
            }
            
            image.classList.remove('lazy');
            imageObserver.unobserve(image);
          }
        });
      });
      
      lazyImages.forEach(image => {
        imageObserver.observe(image);
      });
    } else {
      // Fallback for browsers without IntersectionObserver
      let lazyLoadThrottleTimeout;
      
      function lazyLoad() {
        if (lazyLoadThrottleTimeout) {
          clearTimeout(lazyLoadThrottleTimeout);
        }
        
        lazyLoadThrottleTimeout = setTimeout(() => {
          const scrollTop = window.pageYOffset;
          
          lazyImages.forEach(image => {
            if (image.offsetTop < window.innerHeight + scrollTop) {
              image.src = image.dataset.src;
              
              if (image.dataset.srcset) {
                image.srcset = image.dataset.srcset;
              }
              
              image.classList.remove('lazy');
            }
          });
          
          if (lazyImages.length === 0) {
            document.removeEventListener('scroll', lazyLoad);
            window.removeEventListener('resize', lazyLoad);
            window.removeEventListener('orientationChange', lazyLoad);
          }
        }, 20);
      }
      
      document.addEventListener('scroll', lazyLoad);
      window.addEventListener('resize', lazyLoad);
      window.addEventListener('orientationChange', lazyLoad);
      
      // Initial load
      lazyLoad();
    }
  }

  /**
   * Detect dark mode preference
   * @return {boolean} - Whether dark mode is preferred
   */
  function prefersDarkMode() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  /**
   * Watch for dark mode preference changes
   * @param {Function} callback - Callback function
   */
  function watchDarkModePreference(callback) {
    if (window.matchMedia) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
      
      mediaQuery.addEventListener('change', e => {
        callback(e.matches);
      });
      
      // Initial call
      callback(mediaQuery.matches);
    }
  }

  /**
   * Copy text to clipboard
   * @param {string} text - Text to copy
   * @return {Promise} - Promise that resolves when text is copied
   */
  function copyToClipboard(text) {
    if (navigator.clipboard) {
      return navigator.clipboard.writeText(text);
    } else {
      // Fallback for older browsers
      const textarea = document.createElement('textarea');
      textarea.value = text;
      textarea.style.position = 'fixed';
      document.body.appendChild(textarea);
      textarea.focus();
      textarea.select();
      
      try {
        document.execCommand('copy');
        return Promise.resolve();
      } catch (err) {
        return Promise.reject(err);
      } finally {
        document.body.removeChild(textarea);
      }
    }
  }

  // Export utility functions to global AquaLuxe object
  window.AquaLuxe = window.AquaLuxe || {};
  window.AquaLuxe.utils = {
    debounce,
    throttle,
    getViewportDimensions,
    isInViewport,
    getOffsetTop,
    smoothScrollTo,
    addClassOnScroll,
    formatNumber,
    formatCurrency,
    formatDate,
    getUrlParameters,
    setCookie,
    getCookie,
    deleteCookie,
    showNotification,
    lazyLoadImages,
    prefersDarkMode,
    watchDarkModePreference,
    copyToClipboard
  };

  // Initialize lazy loading
  document.addEventListener('DOMContentLoaded', lazyLoadImages);
})();