/**
 * AquaLuxe Theme Main JavaScript
 *
 * This is the main JavaScript file for the AquaLuxe theme.
 * It imports all modules and initializes the theme functionality.
 */

// Import Styles
import '../css/main.scss';

// Import Alpine.js for interactive components
import Alpine from 'alpinejs';

// Import modules
import DarkMode from './modules/dark-mode';
import Navigation from './modules/navigation';
import Search from './modules/search';
import ScrollEffects from './modules/scroll-effects';
import WooCommerce from './modules/woocommerce';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Document Ready
document.addEventListener('DOMContentLoaded', () => {
  // Initialize modules
  DarkMode.init();
  Navigation.init();
  Search.init();
  ScrollEffects.init();
  
  // Initialize WooCommerce features if WooCommerce is active
  if (document.body.classList.contains('woocommerce')) {
    WooCommerce.init();
  }
  
  // Initialize custom events
  initCustomEvents();
});

/**
 * Initialize custom events
 */
function initCustomEvents() {
  // Trigger event when page is fully loaded
  window.addEventListener('load', () => {
    document.body.classList.add('page-loaded');
    document.dispatchEvent(new CustomEvent('aqualuxe:page-loaded'));
  });
  
  // Trigger event on scroll
  let lastScrollTop = 0;
  window.addEventListener('scroll', () => {
    const st = window.pageYOffset || document.documentElement.scrollTop;
    const scrollDirection = st > lastScrollTop ? 'down' : 'up';
    
    document.dispatchEvent(
      new CustomEvent('aqualuxe:scroll', {
        detail: {
          position: st,
          direction: scrollDirection,
        },
      })
    );
    
    lastScrollTop = st <= 0 ? 0 : st;
  }, { passive: true });
  
  // Trigger event on resize
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      document.dispatchEvent(new CustomEvent('aqualuxe:resize'));
    }, 250);
  });
}