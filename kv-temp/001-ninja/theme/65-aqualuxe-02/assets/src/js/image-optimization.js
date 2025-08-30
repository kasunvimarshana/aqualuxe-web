/**
 * AquaLuxe Theme - Image Optimization
 * 
 * This script handles image optimization features including:
 * - Lazy loading
 * - Responsive images
 * - WebP conversion
 * - Image compression
 * - LQIP (Low Quality Image Placeholders)
 */

// Import dependencies
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import 'lazysizes/plugins/respimg/ls.respimg';
import 'lazysizes/plugins/object-fit/ls.object-fit';
import 'lazysizes/plugins/blur-up/ls.blur-up';

// Configuration
const config = {
  lazyLoadClass: 'lazyload',
  lazyLoadedClass: 'lazyloaded',
  loadingClass: 'lazyloading',
  preloadClass: 'lazypreload',
  errorClass: 'lazyerror',
  autosizesClass: 'lazyautosizes',
  srcAttr: 'data-src',
  srcsetAttr: 'data-srcset',
  sizesAttr: 'data-sizes',
  minBrightness: 0.3,
  blurUpClass: 'blur-up',
  blurUpLoadingClass: 'blur-up-loading',
  blurUpLoadedClass: 'blur-up-loaded',
  blurUpLoadingWidth: 100, // Width of the blur-up image
  autosizesWidthThreshold: 10 // Threshold for autosizes
};

/**
 * Initialize image optimization features
 */
const initImageOptimization = () => {
  // Configure lazySizes
  window.lazySizesConfig = window.lazySizesConfig || {};
  window.lazySizesConfig.lazyClass = config.lazyLoadClass;
  window.lazySizesConfig.loadedClass = config.lazyLoadedClass;
  window.lazySizesConfig.loadingClass = config.loadingClass;
  window.lazySizesConfig.preloadClass = config.preloadClass;
  window.lazySizesConfig.errorClass = config.errorClass;
  window.lazySizesConfig.autosizesClass = config.autosizesClass;
  window.lazySizesConfig.srcAttr = config.srcAttr;
  window.lazySizesConfig.srcsetAttr = config.srcsetAttr;
  window.lazySizesConfig.sizesAttr = config.sizesAttr;
  window.lazySizesConfig.minBrightness = config.minBrightness;
  window.lazySizesConfig.expand = 300; // Load images 300px before they're visible
  window.lazySizesConfig.expFactor = 1.5; // Increase the expand factor
  window.lazySizesConfig.hFac = 0.8; // Height factor
  window.lazySizesConfig.loadMode = 1; // Load all images after the window load event
  window.lazySizesConfig.ricTimeout = 300; // Recalculation timeout
  window.lazySizesConfig.throttleDelay = 125; // Throttle delay

  // Initialize blur-up effect
  initBlurUpEffect();

  // Initialize WebP detection
  detectWebP();

  // Initialize responsive image handling
  initResponsiveImages();

  // Initialize image error handling
  initImageErrorHandling();

  // Initialize native lazy loading as fallback
  initNativeLazyLoading();
};

/**
 * Initialize blur-up effect for images
 */
const initBlurUpEffect = () => {
  document.addEventListener('lazybeforeunveil', (e) => {
    const element = e.target;
    
    if (element.classList.contains(config.blurUpClass)) {
      element.classList.add(config.blurUpLoadingClass);
      element.addEventListener('lazyloaded', () => {
        element.classList.add(config.blurUpLoadedClass);
      });
    }
  });
};

/**
 * Detect WebP support
 */
const detectWebP = () => {
  const webpImage = new Image();
  
  webpImage.onload = function() {
    if (webpImage.height === 2) {
      document.documentElement.classList.add('webp');
    } else {
      document.documentElement.classList.add('no-webp');
    }
  };
  
  webpImage.onerror = function() {
    document.documentElement.classList.add('no-webp');
  };
  
  webpImage.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
};

/**
 * Initialize responsive image handling
 */
const initResponsiveImages = () => {
  // Add data-sizes="auto" to images with class "lazyload"
  const lazyImages = document.querySelectorAll('img.' + config.lazyLoadClass + ':not([data-sizes])');
  
  lazyImages.forEach((img) => {
    if (!img.getAttribute('data-sizes')) {
      img.setAttribute('data-sizes', 'auto');
    }
  });
  
  // Add event listener for window resize to recalculate sizes
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      window.lazySizes.autoSizer.checkElems();
    }, 250);
  });
};

/**
 * Initialize image error handling
 */
const initImageErrorHandling = () => {
  document.addEventListener('lazyunveilread', (e) => {
    const element = e.target;
    
    if (element.tagName === 'IMG') {
      element.addEventListener('error', () => {
        // Add error class
        element.classList.add(config.errorClass);
        
        // Try to load fallback image if available
        const fallbackSrc = element.getAttribute('data-fallback');
        if (fallbackSrc) {
          element.src = fallbackSrc;
        } else {
          // Use default fallback image
          element.src = '/assets/dist/images/placeholder.svg';
        }
      });
    }
  });
};

/**
 * Initialize native lazy loading as fallback
 */
const initNativeLazyLoading = () => {
  if ('loading' in HTMLImageElement.prototype) {
    const lazyImages = document.querySelectorAll('img.' + config.lazyLoadClass);
    
    lazyImages.forEach((img) => {
      // Add native lazy loading attribute
      img.setAttribute('loading', 'lazy');
      
      // If data-src is available, set src
      const dataSrc = img.getAttribute(config.srcAttr);
      if (dataSrc) {
        img.src = dataSrc;
      }
      
      // If data-srcset is available, set srcset
      const dataSrcset = img.getAttribute(config.srcsetAttr);
      if (dataSrcset) {
        img.srcset = dataSrcset;
      }
      
      // If data-sizes is available, set sizes
      const dataSizes = img.getAttribute(config.sizesAttr);
      if (dataSizes && dataSizes !== 'auto') {
        img.sizes = dataSizes;
      }
    });
  }
};

/**
 * Generate responsive image srcset
 * 
 * @param {string} basePath - Base path of the image
 * @param {string} extension - Image extension
 * @param {Array} sizes - Array of sizes
 * @return {string} - Generated srcset
 */
const generateSrcset = (basePath, extension, sizes) => {
  return sizes.map((size) => {
    return `${basePath}-${size}w.${extension} ${size}w`;
  }).join(', ');
};

/**
 * Generate responsive image sizes attribute
 * 
 * @param {Array} breakpoints - Array of breakpoints with sizes
 * @return {string} - Generated sizes attribute
 */
const generateSizes = (breakpoints) => {
  return breakpoints.map((breakpoint) => {
    if (breakpoint.minWidth) {
      return `(min-width: ${breakpoint.minWidth}px) ${breakpoint.size}`;
    } else {
      return breakpoint.size;
    }
  }).join(', ');
};

/**
 * Initialize image optimization on DOM ready
 */
document.addEventListener('DOMContentLoaded', initImageOptimization);

export { initImageOptimization, generateSrcset, generateSizes };