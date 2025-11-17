/**
 * AquaLuxe Navigation JS
 * Handles navigation menu functionality
 */

(function() {
  'use strict';

  // Variables
  const body = document.body;
  const menuToggle = document.getElementById('menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const subMenuToggles = document.querySelectorAll('.sub-menu-toggle');
  const header = document.getElementById('site-header');
  let lastScrollTop = 0;
  
  // Initialize navigation
  function initNavigation() {
    if (!menuToggle || !mobileMenu) return;
    
    // Mobile menu toggle
    menuToggle.addEventListener('click', function() {
      toggleMobileMenu();
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
      if (mobileMenu.classList.contains('visible') && 
          !mobileMenu.contains(event.target) && 
          !menuToggle.contains(event.target)) {
        closeMobileMenu();
      }
    });
    
    // ESC key closes menu
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape' && mobileMenu.classList.contains('visible')) {
        closeMobileMenu();
      }
    });
    
    // Initialize submenu toggles
    initSubMenuToggles();
    
    // Initialize sticky header
    initStickyHeader();
  }
  
  // Toggle mobile menu
  function toggleMobileMenu() {
    if (mobileMenu.classList.contains('hidden')) {
      openMobileMenu();
    } else {
      closeMobileMenu();
    }
  }
  
  // Open mobile menu
  function openMobileMenu() {
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.add('visible');
    menuToggle.setAttribute('aria-expanded', 'true');
    body.classList.add('menu-open');
    
    // Trap focus in mobile menu
    trapFocus(mobileMenu);
  }
  
  // Close mobile menu
  function closeMobileMenu() {
    mobileMenu.classList.remove('visible');
    mobileMenu.classList.add('hidden');
    menuToggle.setAttribute('aria-expanded', 'false');
    body.classList.remove('menu-open');
    
    // Reset all expanded submenus
    const expandedItems = mobileMenu.querySelectorAll('[aria-expanded="true"]');
    expandedItems.forEach(item => {
      item.setAttribute('aria-expanded', 'false');
      const submenu = item.nextElementSibling;
      if (submenu) {
        submenu.style.maxHeight = null;
      }
    });
  }
  
  // Initialize submenu toggles
  function initSubMenuToggles() {
    if (!subMenuToggles.length) return;
    
    subMenuToggles.forEach(toggle => {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', !expanded);
        
        const submenu = toggle.nextElementSibling;
        if (!submenu) return;
        
        if (!expanded) {
          submenu.style.maxHeight = submenu.scrollHeight + 'px';
        } else {
          submenu.style.maxHeight = null;
        }
      });
    });
  }
  
  // Initialize sticky header
  function initStickyHeader() {
    if (!header) return;
    
    window.addEventListener('scroll', function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      // Add sticky class when scrolling down
      if (scrollTop > 100) {
        header.classList.add('sticky-header');
      } else {
        header.classList.remove('sticky-header');
      }
      
      // Hide/show header based on scroll direction
      if (scrollTop > lastScrollTop && scrollTop > 200) {
        // Scrolling down
        header.classList.add('header-hidden');
      } else {
        // Scrolling up
        header.classList.remove('header-hidden');
      }
      
      lastScrollTop = scrollTop;
    });
  }
  
  // Trap focus in an element (for accessibility)
  function trapFocus(element) {
    const focusableElements = element.querySelectorAll(
      'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
    );
    
    if (!focusableElements.length) return;
    
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    // Focus the first element
    firstElement.focus();
    
    // Trap focus in the element
    element.addEventListener('keydown', function(e) {
      if (e.key === 'Tab') {
        if (e.shiftKey) {
          // Shift + Tab
          if (document.activeElement === firstElement) {
            e.preventDefault();
            lastElement.focus();
          }
        } else {
          // Tab
          if (document.activeElement === lastElement) {
            e.preventDefault();
            firstElement.focus();
          }
        }
      }
    });
  }
  
  // Initialize when DOM is loaded
  document.addEventListener('DOMContentLoaded', initNavigation);
})();