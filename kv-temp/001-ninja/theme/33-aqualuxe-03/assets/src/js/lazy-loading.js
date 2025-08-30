/**
 * AquaLuxe Theme - Lazy Loading
 *
 * This file handles lazy loading of images and iframes using
 * Intersection Observer API with a fallback for older browsers.
 */

(function() {
  'use strict';
  
  // Check if browser supports Intersection Observer
  const hasIntersectionObserver = 'IntersectionObserver' in window;
  
  // Options for Intersection Observer
  const observerOptions = {
    root: null, // Use viewport as root
    rootMargin: '200px 0px', // Start loading 200px before element enters viewport
    threshold: 0.01 // Trigger when 1% of element is visible
  };
  
  /**
   * Initialize lazy loading
   */
  function init() {
    if (hasIntersectionObserver) {
      initIntersectionObserver();
    } else {
      loadLazyElements();
      
      // Fallback to scroll event for older browsers
      window.addEventListener('scroll', throttle(loadLazyElements, 200));
      window.addEventListener('resize', throttle(loadLazyElements, 200));
      window.addEventListener('orientationchange', throttle(loadLazyElements, 200));
    }
  }
  
  /**
   * Initialize Intersection Observer for lazy loading
   */
  function initIntersectionObserver() {
    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          const element = entry.target;
          loadElement(element);
          observer.unobserve(element);
        }
      });
    }, observerOptions);
    
    // Get all elements with data-src or data-srcset
    const lazyElements = document.querySelectorAll('[data-src], [data-srcset], [data-bg]');
    
    lazyElements.forEach(function(element) {
      observer.observe(element);
    });
  }
  
  /**
   * Load all lazy elements (fallback for browsers without Intersection Observer)
   */
  function loadLazyElements() {
    const lazyElements = document.querySelectorAll('[data-src], [data-srcset], [data-bg]');
    
    lazyElements.forEach(function(element) {
      if (isElementInViewport(element)) {
        loadElement(element);
      }
    });
  }
  
  /**
   * Load a single lazy element
   * @param {Element} element - The element to load
   */
  function loadElement(element) {
    // Handle different element types
    if (element.tagName.toLowerCase() === 'img') {
      loadImage(element);
    } else if (element.tagName.toLowerCase() === 'iframe') {
      loadIframe(element);
    } else if (element.hasAttribute('data-bg')) {
      loadBackgroundImage(element);
    }
    
    // Remove lazy loading class
    element.classList.remove('lazy');
    
    // Add loaded class
    element.classList.add('lazy-loaded');
    
    // Dispatch event when element is loaded
    element.dispatchEvent(new CustomEvent('lazyloaded'));
  }
  
  /**
   * Load an image element
   * @param {Element} img - The image element to load
   */
  function loadImage(img) {
    const src = img.getAttribute('data-src');
    const srcset = img.getAttribute('data-srcset');
    const sizes = img.getAttribute('data-sizes');
    
    // Set srcset if available
    if (srcset) {
      img.srcset = srcset;
    }
    
    // Set sizes if available
    if (sizes) {
      img.sizes = sizes;
    }
    
    // Set src (should be last for browser compatibility)
    if (src) {
      img.src = src;
    }
    
    // Remove data attributes
    img.removeAttribute('data-src');
    img.removeAttribute('data-srcset');
    img.removeAttribute('data-sizes');
  }
  
  /**
   * Load an iframe element
   * @param {Element} iframe - The iframe element to load
   */
  function loadIframe(iframe) {
    const src = iframe.getAttribute('data-src');
    
    if (src) {
      iframe.src = src;
    }
    
    // Remove data attribute
    iframe.removeAttribute('data-src');
  }
  
  /**
   * Load a background image
   * @param {Element} element - The element to apply background image to
   */
  function loadBackgroundImage(element) {
    const src = element.getAttribute('data-bg');
    
    if (src) {
      element.style.backgroundImage = 'url(' + src + ')';
    }
    
    // Remove data attribute
    element.removeAttribute('data-bg');
  }
  
  /**
   * Check if element is in viewport
   * @param {Element} element - The element to check
   * @return {boolean} True if element is in viewport
   */
  function isElementInViewport(element) {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    const windowWidth = window.innerWidth || document.documentElement.clientWidth;
    
    // Add some margin to start loading before element enters viewport
    const verticalMargin = 200;
    const horizontalMargin = 0;
    
    return (
      rect.bottom >= (0 - verticalMargin) &&
      rect.right >= (0 - horizontalMargin) &&
      rect.top <= (windowHeight + verticalMargin) &&
      rect.left <= (windowWidth + horizontalMargin)
    );
  }
  
  /**
   * Throttle function to limit how often a function can be called
   * @param {Function} func - The function to throttle
   * @param {number} limit - The time limit in milliseconds
   * @return {Function} Throttled function
   */
  function throttle(func, limit) {
    let inThrottle;
    
    return function() {
      const args = arguments;
      const context = this;
      
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(function() {
          inThrottle = false;
        }, limit);
      }
    };
  }
  
  /**
   * Add lazy loading to newly added elements (e.g., via AJAX)
   * @param {NodeList|Element[]} elements - The elements to observe
   */
  function addLazyLoadToElements(elements) {
    if (!elements || !elements.length) {
      return;
    }
    
    if (hasIntersectionObserver) {
      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            const element = entry.target;
            loadElement(element);
            observer.unobserve(element);
          }
        });
      }, observerOptions);
      
      elements.forEach(function(element) {
        observer.observe(element);
      });
    } else {
      elements.forEach(function(element) {
        if (isElementInViewport(element)) {
          loadElement(element);
        }
      });
    }
  }
  
  // Expose public API
  window.AquaLuxeLazyLoad = {
    init: init,
    loadElement: loadElement,
    addLazyLoadToElements: addLazyLoadToElements
  };
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();