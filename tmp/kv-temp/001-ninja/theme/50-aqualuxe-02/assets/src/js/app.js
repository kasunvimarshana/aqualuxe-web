/**
 * AquaLuxe Theme Main JavaScript
 *
 * This is the main JavaScript file for the AquaLuxe theme.
 * It handles all the interactive elements and functionality.
 */

// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Import modules
import './modules/navigation';
import './modules/dark-mode';
import './modules/search';
import './modules/slider';
import './modules/modal';
import './modules/accordion';
import './modules/tabs';
import './modules/animations';
import './modules/forms';
import './modules/lazy-loading';
import './modules/responsive';

// Import WooCommerce specific modules if WooCommerce is active
if (typeof woocommerce !== 'undefined') {
  import('./modules/woocommerce/quick-view');
  import('./modules/woocommerce/cart');
  import('./modules/woocommerce/checkout');
  import('./modules/woocommerce/wishlist');
  import('./modules/woocommerce/product-gallery');
  import('./modules/woocommerce/filters');
}

// Initialize Alpine.js
Alpine.start();

/**
 * Document ready function
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize components
  initComponents();
  
  // Handle responsive behavior
  handleResponsive();
  
  // Initialize lazy loading
  initLazyLoading();
  
  // Initialize animations
  initAnimations();
});

/**
 * Initialize all components
 */
function initComponents() {
  // Initialize navigation
  if (typeof initNavigation === 'function') {
    initNavigation();
  }
  
  // Initialize dark mode
  if (typeof initDarkMode === 'function') {
    initDarkMode();
  }
  
  // Initialize search
  if (typeof initSearch === 'function') {
    initSearch();
  }
  
  // Initialize sliders
  if (typeof initSliders === 'function') {
    initSliders();
  }
  
  // Initialize modals
  if (typeof initModals === 'function') {
    initModals();
  }
  
  // Initialize accordions
  if (typeof initAccordions === 'function') {
    initAccordions();
  }
  
  // Initialize tabs
  if (typeof initTabs === 'function') {
    initTabs();
  }
  
  // Initialize forms
  if (typeof initForms === 'function') {
    initForms();
  }
}

/**
 * Handle responsive behavior
 */
function handleResponsive() {
  const breakpoints = {
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280,
    '2xl': 1536
  };
  
  // Check current breakpoint
  function getCurrentBreakpoint() {
    const width = window.innerWidth;
    
    if (width < breakpoints.sm) {
      return 'xs';
    } else if (width < breakpoints.md) {
      return 'sm';
    } else if (width < breakpoints.lg) {
      return 'md';
    } else if (width < breakpoints.xl) {
      return 'lg';
    } else if (width < breakpoints['2xl']) {
      return 'xl';
    } else {
      return '2xl';
    }
  }
  
  // Handle resize events
  let currentBreakpoint = getCurrentBreakpoint();
  
  window.addEventListener('resize', function() {
    const newBreakpoint = getCurrentBreakpoint();
    
    if (newBreakpoint !== currentBreakpoint) {
      currentBreakpoint = newBreakpoint;
      document.body.dispatchEvent(new CustomEvent('breakpoint-change', { detail: { breakpoint: currentBreakpoint } }));
    }
  });
}

/**
 * Initialize lazy loading
 */
function initLazyLoading() {
  if ('loading' in HTMLImageElement.prototype) {
    // Browser supports native lazy loading
    const lazyImages = document.querySelectorAll('img.lazy');
    lazyImages.forEach(img => {
      img.src = img.dataset.src;
      if (img.dataset.srcset) {
        img.srcset = img.dataset.srcset;
      }
      img.classList.remove('lazy');
    });
  } else {
    // Fallback for browsers that don't support native lazy loading
    if (typeof initLazyLoadingFallback === 'function') {
      initLazyLoadingFallback();
    }
  }
}

/**
 * Initialize animations
 */
function initAnimations() {
  if (typeof initScrollAnimations === 'function') {
    initScrollAnimations();
  }
}