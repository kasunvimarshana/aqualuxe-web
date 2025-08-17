/**
 * AquaLuxe WordPress Theme
 * Main JavaScript File
 */

// Import modules
import './modules/navigation';
import './modules/accessibility';
import './modules/utils';

// Import components
import './components/carousel';
import './components/modal';
import './components/tabs';
import './components/accordion';

// Initialize Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/**
 * Document Ready Function
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize theme
  AquaLuxe.init();
});

/**
 * AquaLuxe Theme Object
 */
const AquaLuxe = {
  /**
   * Initialize the theme
   */
  init: function() {
    this.setupEventListeners();
    this.initializeComponents();
    this.handleResponsiveElements();
  },

  /**
   * Set up event listeners
   */
  setupEventListeners: function() {
    // Handle scroll events
    window.addEventListener('scroll', this.handleScroll);
    
    // Handle resize events
    window.addEventListener('resize', this.debounce(this.handleResize, 250));
    
    // Handle theme mode toggle
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
      themeToggle.addEventListener('click', this.toggleThemeMode);
    }
  },

  /**
   * Initialize components
   */
  initializeComponents: function() {
    // Initialize carousels
    const carousels = document.querySelectorAll('.carousel');
    if (carousels.length) {
      carousels.forEach(carousel => {
        new AquaLuxe.Carousel(carousel);
      });
    }

    // Initialize modals
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    if (modalTriggers.length) {
      modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', this.openModal);
      });
    }

    // Initialize tabs
    const tabContainers = document.querySelectorAll('.tabs-container');
    if (tabContainers.length) {
      tabContainers.forEach(container => {
        new AquaLuxe.Tabs(container);
      });
    }

    // Initialize accordions
    const accordions = document.querySelectorAll('.accordion');
    if (accordions.length) {
      accordions.forEach(accordion => {
        new AquaLuxe.Accordion(accordion);
      });
    }
  },

  /**
   * Handle responsive elements
   */
  handleResponsiveElements: function() {
    const isMobile = window.innerWidth < 768;
    const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
    const isDesktop = window.innerWidth >= 1024;

    // Handle mobile menu
    const mobileMenu = document.querySelector('.mobile-menu');
    const desktopMenu = document.querySelector('.desktop-menu');
    
    if (mobileMenu && desktopMenu) {
      if (isMobile || isTablet) {
        mobileMenu.setAttribute('aria-hidden', 'false');
        desktopMenu.setAttribute('aria-hidden', 'true');
      } else {
        mobileMenu.setAttribute('aria-hidden', 'true');
        desktopMenu.setAttribute('aria-hidden', 'false');
      }
    }
  },

  /**
   * Handle scroll events
   */
  handleScroll: function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const header = document.querySelector('.site-header');
    
    // Add sticky class to header when scrolled
    if (header) {
      if (scrollTop > 100) {
        header.classList.add('is-sticky');
      } else {
        header.classList.remove('is-sticky');
      }
    }
    
    // Handle scroll to top button
    const scrollToTopBtn = document.querySelector('.scroll-to-top');
    if (scrollToTopBtn) {
      if (scrollTop > 300) {
        scrollToTopBtn.classList.add('is-visible');
      } else {
        scrollToTopBtn.classList.remove('is-visible');
      }
    }
  },

  /**
   * Handle resize events
   */
  handleResize: function() {
    AquaLuxe.handleResponsiveElements();
  },

  /**
   * Toggle theme mode (light/dark)
   */
  toggleThemeMode: function() {
    const html = document.documentElement;
    const currentMode = html.getAttribute('data-theme') || 'light';
    const newMode = currentMode === 'light' ? 'dark' : 'light';
    
    html.setAttribute('data-theme', newMode);
    localStorage.setItem('theme-mode', newMode);
    
    // Dispatch custom event
    document.dispatchEvent(new CustomEvent('themeChanged', { detail: { mode: newMode } }));
  },

  /**
   * Open modal
   * @param {Event} e - Click event
   */
  openModal: function(e) {
    e.preventDefault();
    const target = this.getAttribute('data-modal-target');
    const modal = document.getElementById(target);
    
    if (modal) {
      modal.classList.add('is-active');
      document.body.classList.add('modal-open');
      
      // Close modal when clicking on close button
      const closeBtn = modal.querySelector('.modal-close');
      if (closeBtn) {
        closeBtn.addEventListener('click', function() {
          modal.classList.remove('is-active');
          document.body.classList.remove('modal-open');
        });
      }
      
      // Close modal when clicking on overlay
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          modal.classList.remove('is-active');
          document.body.classList.remove('modal-open');
        }
      });
    }
  },

  /**
   * Debounce function
   * @param {Function} func - Function to debounce
   * @param {number} wait - Wait time in milliseconds
   * @return {Function} - Debounced function
   */
  debounce: function(func, wait) {
    let timeout;
    return function() {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(function() {
        func.apply(context, args);
      }, wait);
    };
  }
};

// Export AquaLuxe object
window.AquaLuxe = AquaLuxe;