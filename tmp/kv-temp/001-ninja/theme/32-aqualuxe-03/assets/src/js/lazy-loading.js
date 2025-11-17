/**
 * AquaLuxe Theme Lazy Loading
 *
 * This script implements lazy loading for images, iframes, videos, and HTML content
 * using the Intersection Observer API with fallbacks for older browsers.
 */

(function() {
  'use strict';

  // Configuration
  const config = {
    rootMargin: '200px 0px', // Load elements when they are 200px from viewport
    threshold: 0.01, // Trigger when at least 1% of the element is visible
    loadingClass: 'is-loading',
    loadedClass: 'is-loaded',
    errorClass: 'has-error',
    loadingAttribute: 'data-loading',
    srcAttribute: 'data-src',
    srcsetAttribute: 'data-srcset',
    sizesAttribute: 'data-sizes',
    backgroundAttribute: 'data-background',
    posterAttribute: 'data-poster',
    sourceAttribute: 'data-source',
    lazyClass: 'lazy',
    lazyHtmlClass: 'lazy-html',
    lazyBackgroundClass: 'lazy-background',
    lazyVideoClass: 'lazy-video',
    lazyIframeClass: 'lazy-iframe',
    lazyPictureClass: 'lazy-picture',
    lazyPriorityClass: 'lazy-priority',
    nativeLazySupport: 'loading' in HTMLImageElement.prototype,
    observerSupport: 'IntersectionObserver' in window
  };

  // Elements to lazy load
  let lazyElements = [];
  let priorityElements = [];

  // Intersection Observer instance
  let observer;

  /**
   * Initialize lazy loading
   */
  function initialize() {
    // Get all elements to lazy load
    lazyElements = document.querySelectorAll(
      `.${config.lazyClass}, .${config.lazyHtmlClass}, .${config.lazyBackgroundClass}, .${config.lazyVideoClass}, .${config.lazyIframeClass}, .${config.lazyPictureClass}`
    );

    // Get priority elements
    priorityElements = document.querySelectorAll(`.${config.lazyPriorityClass}`);

    // Load priority elements immediately
    if (priorityElements.length > 0) {
      Array.from(priorityElements).forEach(element => {
        loadElement(element);
      });
    }

    // Use Intersection Observer if supported
    if (config.observerSupport) {
      observer = new IntersectionObserver(onIntersection, {
        rootMargin: config.rootMargin,
        threshold: config.threshold
      });

      Array.from(lazyElements).forEach(element => {
        // Skip priority elements as they are already loaded
        if (!element.classList.contains(config.lazyPriorityClass)) {
          observer.observe(element);
        }
      });
    } else {
      // Fallback for browsers that don't support Intersection Observer
      loadElementsInViewport();
      
      // Add scroll and resize event listeners
      window.addEventListener('scroll', throttle(loadElementsInViewport, 200));
      window.addEventListener('resize', throttle(loadElementsInViewport, 200));
    }
  }

  /**
   * Intersection Observer callback
   *
   * @param {IntersectionObserverEntry[]} entries Intersection observer entries
   */
  function onIntersection(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Stop observing the element
        observer.unobserve(entry.target);
        
        // Load the element
        loadElement(entry.target);
      }
    });
  }

  /**
   * Load elements in viewport (fallback for browsers without Intersection Observer)
   */
  function loadElementsInViewport() {
    Array.from(lazyElements).forEach(element => {
      if (isElementInViewport(element) && !element.classList.contains(config.loadedClass)) {
        loadElement(element);
      }
    });
  }

  /**
   * Check if element is in viewport
   *
   * @param {HTMLElement} element The element to check
   * @returns {boolean} True if element is in viewport
   */
  function isElementInViewport(element) {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    const windowWidth = window.innerWidth || document.documentElement.clientWidth;
    
    // Consider the element in viewport if it's within 200px of the viewport
    const verticalInView = rect.top - 200 <= windowHeight && rect.bottom + 200 >= 0;
    const horizontalInView = rect.left - 200 <= windowWidth && rect.right + 200 >= 0;
    
    return verticalInView && horizontalInView;
  }

  /**
   * Load element based on its type
   *
   * @param {HTMLElement} element The element to load
   */
  function loadElement(element) {
    // Set loading state
    element.classList.add(config.loadingClass);
    
    try {
      // Load based on element type
      if (element.classList.contains(config.lazyClass)) {
        loadImage(element);
      } else if (element.classList.contains(config.lazyPictureClass)) {
        loadPicture(element);
      } else if (element.classList.contains(config.lazyBackgroundClass)) {
        loadBackground(element);
      } else if (element.classList.contains(config.lazyVideoClass)) {
        loadVideo(element);
      } else if (element.classList.contains(config.lazyIframeClass)) {
        loadIframe(element);
      } else if (element.classList.contains(config.lazyHtmlClass)) {
        loadHtml(element);
      }
    } catch (error) {
      console.error('Error loading element:', error);
      element.classList.add(config.errorClass);
    }
  }

  /**
   * Load image element
   *
   * @param {HTMLImageElement} img The image element to load
   */
  function loadImage(img) {
    // Get attributes
    const src = img.getAttribute(config.srcAttribute);
    const srcset = img.getAttribute(config.srcsetAttribute);
    const sizes = img.getAttribute(config.sizesAttribute);
    
    // Set attributes
    if (srcset) {
      img.srcset = srcset;
    }
    
    if (sizes) {
      img.sizes = sizes;
    }
    
    if (src) {
      img.src = src;
    }
    
    // Use native lazy loading if supported
    if (config.nativeLazySupport) {
      img.loading = 'lazy';
    }
    
    // Handle load event
    img.addEventListener('load', () => {
      img.classList.remove(config.loadingClass);
      img.classList.add(config.loadedClass);
      
      // Remove data attributes
      img.removeAttribute(config.srcAttribute);
      img.removeAttribute(config.srcsetAttribute);
      img.removeAttribute(config.sizesAttribute);
    });
    
    // Handle error event
    img.addEventListener('error', () => {
      img.classList.remove(config.loadingClass);
      img.classList.add(config.errorClass);
    });
  }

  /**
   * Load picture element
   *
   * @param {HTMLElement} picture The picture element to load
   */
  function loadPicture(picture) {
    // Get all source elements
    const sources = picture.querySelectorAll('source');
    
    // Get the image element
    const img = picture.querySelector('img');
    
    // Load sources
    Array.from(sources).forEach(source => {
      const srcset = source.getAttribute(config.srcsetAttribute);
      const sizes = source.getAttribute(config.sizesAttribute);
      
      if (srcset) {
        source.srcset = srcset;
      }
      
      if (sizes) {
        source.sizes = sizes;
      }
      
      // Remove data attributes
      source.removeAttribute(config.srcsetAttribute);
      source.removeAttribute(config.sizesAttribute);
    });
    
    // Load image
    if (img) {
      loadImage(img);
    }
    
    // Mark picture as loaded
    picture.classList.remove(config.loadingClass);
    picture.classList.add(config.loadedClass);
  }

  /**
   * Load background image
   *
   * @param {HTMLElement} element The element to load background image for
   */
  function loadBackground(element) {
    const background = element.getAttribute(config.backgroundAttribute);
    
    if (background) {
      // Create a new image to preload
      const img = new Image();
      
      // Set load event
      img.onload = () => {
        element.style.backgroundImage = `url('${background}')`;
        element.classList.remove(config.loadingClass);
        element.classList.add(config.loadedClass);
        element.removeAttribute(config.backgroundAttribute);
      };
      
      // Set error event
      img.onerror = () => {
        element.classList.remove(config.loadingClass);
        element.classList.add(config.errorClass);
      };
      
      // Load the image
      img.src = background;
    }
  }

  /**
   * Load video element
   *
   * @param {HTMLVideoElement} video The video element to load
   */
  function loadVideo(video) {
    // Get attributes
    const src = video.getAttribute(config.srcAttribute);
    const poster = video.getAttribute(config.posterAttribute);
    
    // Set poster if available
    if (poster) {
      video.poster = poster;
      video.removeAttribute(config.posterAttribute);
    }
    
    // Get all source elements
    const sources = video.querySelectorAll('source');
    
    if (sources.length > 0) {
      // Load sources
      Array.from(sources).forEach(source => {
        const src = source.getAttribute(config.sourceAttribute);
        
        if (src) {
          source.src = src;
          source.removeAttribute(config.sourceAttribute);
        }
      });
    } else if (src) {
      // Set src attribute
      video.src = src;
      video.removeAttribute(config.srcAttribute);
    }
    
    // Load the video
    if (video.getAttribute('preload') !== 'none') {
      video.load();
    }
    
    // Mark as loaded
    video.classList.remove(config.loadingClass);
    video.classList.add(config.loadedClass);
  }

  /**
   * Load iframe element
   *
   * @param {HTMLIFrameElement} iframe The iframe element to load
   */
  function loadIframe(iframe) {
    // Get src attribute
    const src = iframe.getAttribute(config.srcAttribute);
    
    if (src) {
      // Set src attribute
      iframe.src = src;
      
      // Handle load event
      iframe.addEventListener('load', () => {
        iframe.classList.remove(config.loadingClass);
        iframe.classList.add(config.loadedClass);
        iframe.removeAttribute(config.srcAttribute);
      });
      
      // Handle error event
      iframe.addEventListener('error', () => {
        iframe.classList.remove(config.loadingClass);
        iframe.classList.add(config.errorClass);
      });
    }
  }

  /**
   * Load HTML content
   *
   * @param {HTMLElement} element The element to load HTML content for
   */
  function loadHtml(element) {
    // Get source attribute
    const src = element.getAttribute(config.sourceAttribute);
    
    if (src) {
      // Fetch HTML content
      fetch(src)
        .then(response => {
          if (!response.ok) {
            throw new Error(`Failed to load HTML content: ${response.status} ${response.statusText}`);
          }
          
          return response.text();
        })
        .then(html => {
          // Set HTML content
          element.innerHTML = html;
          
          // Mark as loaded
          element.classList.remove(config.loadingClass);
          element.classList.add(config.loadedClass);
          element.removeAttribute(config.sourceAttribute);
          
          // Initialize lazy loading for new elements
          const newLazyElements = element.querySelectorAll(
            `.${config.lazyClass}, .${config.lazyHtmlClass}, .${config.lazyBackgroundClass}, .${config.lazyVideoClass}, .${config.lazyIframeClass}, .${config.lazyPictureClass}`
          );
          
          if (newLazyElements.length > 0) {
            Array.from(newLazyElements).forEach(newElement => {
              if (config.observerSupport) {
                observer.observe(newElement);
              } else if (isElementInViewport(newElement)) {
                loadElement(newElement);
              }
            });
            
            // Add new elements to the lazy elements array
            lazyElements = [...lazyElements, ...newLazyElements];
          }
        })
        .catch(error => {
          console.error(error);
          element.classList.remove(config.loadingClass);
          element.classList.add(config.errorClass);
        });
    }
  }

  /**
   * Throttle function to limit the rate at which a function can fire
   *
   * @param {Function} func The function to throttle
   * @param {number} limit The time limit in milliseconds
   * @returns {Function} Throttled function
   */
  function throttle(func, limit) {
    let inThrottle;
    
    return function() {
      const args = arguments;
      const context = this;
      
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        
        setTimeout(() => {
          inThrottle = false;
        }, limit);
      }
    };
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialize);
  } else {
    initialize();
  }

  // Public API
  window.AquaLuxeLazyLoad = {
    /**
     * Refresh lazy loading (e.g., after adding new elements to the DOM)
     */
    refresh: function() {
      // Get all elements to lazy load
      lazyElements = document.querySelectorAll(
        `.${config.lazyClass}, .${config.lazyHtmlClass}, .${config.lazyBackgroundClass}, .${config.lazyVideoClass}, .${config.lazyIframeClass}, .${config.lazyPictureClass}`
      );
      
      // Use Intersection Observer if supported
      if (config.observerSupport) {
        Array.from(lazyElements).forEach(element => {
          // Skip already loaded elements
          if (!element.classList.contains(config.loadedClass) && !element.classList.contains(config.loadingClass)) {
            observer.observe(element);
          }
        });
      } else {
        // Fallback for browsers that don't support Intersection Observer
        loadElementsInViewport();
      }
    },
    
    /**
     * Load an element immediately
     *
     * @param {HTMLElement} element The element to load
     */
    loadNow: function(element) {
      loadElement(element);
    }
  };
})();