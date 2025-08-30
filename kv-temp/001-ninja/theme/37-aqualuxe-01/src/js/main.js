/**
 * AquaLuxe Theme Main JavaScript
 * This file handles the main JavaScript functionality for the theme
 */

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';

// Initialize Alpine.js plugins
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(persist);

// Import blocks if we're in the admin area
if (typeof wp !== 'undefined' && wp.blocks) {
  import('./blocks');
}

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Alpine.js
  window.Alpine = Alpine;
  Alpine.start();
  
  // Initialize theme functionality
  AquaLuxe.init();
});

/**
 * AquaLuxe Theme Namespace
 * Contains all theme-specific functionality
 */
const AquaLuxe = {
  /**
   * Initialize all theme functionality
   */
  init() {
    this.setupDarkMode();
    this.setupMobileMenu();
    this.setupStickyHeader();
    this.setupScrollToTop();
    this.setupAccessibility();
    this.setupSearchModal();
    
    // Initialize WooCommerce specific functionality if WooCommerce is active
    if (document.body.classList.contains('woocommerce-active')) {
      this.setupWooCommerce();
    }
  },
  
  /**
   * Setup dark mode toggle functionality
   */
  setupDarkMode() {
    // Check for saved theme preference or respect OS preference
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    
    // Setup dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
      darkModeToggle.addEventListener('click', () => {
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('theme', 'light');
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('theme', 'dark');
        }
      });
    }
  },
  
  /**
   * Setup mobile menu functionality
   */
  setupMobileMenu() {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener('click', () => {
        const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
        menuToggle.setAttribute('aria-expanded', !expanded);
        mobileMenu.classList.toggle('hidden');
      });
      
      // Close mobile menu on window resize (if desktop view)
      window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) { // lg breakpoint
          mobileMenu.classList.add('hidden');
          menuToggle.setAttribute('aria-expanded', 'false');
        }
      });
    }
    
    // Setup sub-menu toggles for mobile
    const subMenuToggles = document.querySelectorAll('.sub-menu-toggle');
    subMenuToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const expanded = toggle.getAttribute('aria-expanded') === 'true' || false;
        toggle.setAttribute('aria-expanded', !expanded);
        
        const subMenu = toggle.nextElementSibling;
        if (subMenu && subMenu.classList.contains('sub-menu')) {
          subMenu.classList.toggle('hidden');
        }
      });
    });
  },
  
  /**
   * Setup sticky header functionality
   */
  setupStickyHeader() {
    const header = document.getElementById('masthead');
    if (!header) return;
    
    const headerHeight = header.offsetHeight;
    const headerOffset = header.offsetTop;
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      // Add sticky class when scrolled past header height
      if (scrollTop > headerOffset + headerHeight) {
        header.classList.add('sticky', 'top-0', 'z-50', 'animate-slideDown', 'shadow-md');
      } else {
        header.classList.remove('sticky', 'top-0', 'z-50', 'animate-slideDown', 'shadow-md');
      }
      
      // Hide header when scrolling down, show when scrolling up
      if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
        header.classList.add('transform', '-translate-y-full');
      } else {
        header.classList.remove('transform', '-translate-y-full');
      }
      
      lastScrollTop = scrollTop;
    });
  },
  
  /**
   * Setup scroll to top button
   */
  setupScrollToTop() {
    const scrollToTopBtn = document.getElementById('scroll-to-top');
    if (!scrollToTopBtn) return;
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.remove('opacity-0', 'invisible');
        scrollToTopBtn.classList.add('opacity-100', 'visible');
      } else {
        scrollToTopBtn.classList.add('opacity-0', 'invisible');
        scrollToTopBtn.classList.remove('opacity-100', 'visible');
      }
    });
    
    // Scroll to top when clicked
    scrollToTopBtn.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  },
  
  /**
   * Setup accessibility enhancements
   */
  setupAccessibility() {
    // Add focus styles for keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        document.body.classList.add('user-is-tabbing');
      }
    });
    
    document.addEventListener('mousedown', () => {
      document.body.classList.remove('user-is-tabbing');
    });
    
    // Make dropdown menus accessible via keyboard
    const menuItems = document.querySelectorAll('.menu-item-has-children');
    menuItems.forEach(item => {
      item.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const subMenu = item.querySelector('.sub-menu');
          if (subMenu) {
            const isHidden = subMenu.classList.contains('hidden');
            if (isHidden) {
              subMenu.classList.remove('hidden');
            } else {
              subMenu.classList.add('hidden');
            }
          }
        }
      });
    });
  },
  
  /**
   * Setup search modal functionality
   */
  setupSearchModal() {
    const searchToggle = document.getElementById('search-toggle');
    const searchModal = document.getElementById('search-modal');
    const searchClose = document.getElementById('search-close');
    const searchInput = document.getElementById('search-input');
    
    if (!searchToggle || !searchModal) return;
    
    // Open search modal
    searchToggle.addEventListener('click', (e) => {
      e.preventDefault();
      searchModal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      
      // Focus search input
      if (searchInput) {
        setTimeout(() => {
          searchInput.focus();
        }, 100);
      }
    });
    
    // Close search modal
    if (searchClose) {
      searchClose.addEventListener('click', () => {
        searchModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      });
    }
    
    // Close search modal on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
        searchModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    });
    
    // Close search modal on outside click
    searchModal.addEventListener('click', (e) => {
      if (e.target === searchModal) {
        searchModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    });
  },
  
  /**
   * Setup WooCommerce specific functionality
   */
  setupWooCommerce() {
    // Quantity input buttons
    const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
    quantityInputs.forEach(input => {
      const wrapper = document.createElement('div');
      wrapper.className = 'flex items-center border border-secondary-300 dark:border-secondary-700 rounded-md overflow-hidden';
      
      const minusBtn = document.createElement('button');
      minusBtn.type = 'button';
      minusBtn.className = 'px-3 py-1 bg-secondary-100 dark:bg-secondary-800 text-secondary-700 dark:text-white hover:bg-secondary-200 dark:hover:bg-secondary-700';
      minusBtn.textContent = '-';
      minusBtn.addEventListener('click', () => {
        const currentValue = parseInt(input.value);
        if (currentValue > parseInt(input.min)) {
          input.value = currentValue - 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      const plusBtn = document.createElement('button');
      plusBtn.type = 'button';
      plusBtn.className = 'px-3 py-1 bg-secondary-100 dark:bg-secondary-800 text-secondary-700 dark:text-white hover:bg-secondary-200 dark:hover:bg-secondary-700';
      plusBtn.textContent = '+';
      plusBtn.addEventListener('click', () => {
        const currentValue = parseInt(input.value);
        if (!input.max || currentValue < parseInt(input.max)) {
          input.value = currentValue + 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      // Replace input with custom control
      const parent = input.parentNode;
      wrapper.appendChild(minusBtn);
      wrapper.appendChild(input);
      wrapper.appendChild(plusBtn);
      parent.appendChild(wrapper);
    });
    
    // Product gallery image zoom
    const productImages = document.querySelectorAll('.woocommerce-product-gallery__image a');
    productImages.forEach(image => {
      image.addEventListener('mouseenter', () => {
        image.classList.add('transition-transform', 'duration-300', 'transform', 'scale-105');
      });
      
      image.addEventListener('mouseleave', () => {
        image.classList.remove('transition-transform', 'duration-300', 'transform', 'scale-105');
      });
    });
    
    // Ajax add to cart
    const addToCartButtons = document.querySelectorAll('.add_to_cart_button');
    addToCartButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        button.classList.add('loading');
      });
    });
    
    // Update mini cart on ajax add to cart
    document.body.addEventListener('added_to_cart', (e, fragments, cart_hash, button) => {
      if (button) {
        button.classList.remove('loading');
        button.classList.add('added');
      }
      
      // Show mini cart notification
      const miniCartCount = document.querySelector('.cart-count');
      if (miniCartCount && fragments['.cart-count']) {
        miniCartCount.innerHTML = fragments['.cart-count'];
        
        // Animate mini cart icon
        miniCartCount.classList.add('animate-pulse');
        setTimeout(() => {
          miniCartCount.classList.remove('animate-pulse');
        }, 1000);
      }
    });
  }
};

// Export for potential use in other modules
export default AquaLuxe;