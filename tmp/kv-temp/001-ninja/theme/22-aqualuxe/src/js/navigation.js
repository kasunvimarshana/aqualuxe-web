/**
 * AquaLuxe Theme Navigation
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

document.addEventListener('DOMContentLoaded', function() {
  const siteNavigation = document.getElementById('site-navigation');
  const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
  const mobileMenuContainer = document.getElementById('mobile-menu-container');
  const mobileMenuClose = document.getElementById('mobile-menu-close');
  
  // Return early if the navigation doesn't exist.
  if (!siteNavigation || !mobileMenuToggle || !mobileMenuContainer) {
    return;
  }
  
  // Toggle mobile menu
  mobileMenuToggle.addEventListener('click', function() {
    mobileMenuToggle.setAttribute('aria-expanded', 'true');
    mobileMenuContainer.classList.remove('hidden');
    mobileMenuContainer.classList.add('block');
    
    // Trap focus in mobile menu
    trapFocus(mobileMenuContainer);
    
    // Prevent body scrolling
    document.body.classList.add('overflow-hidden');
  });
  
  // Close mobile menu
  if (mobileMenuClose) {
    mobileMenuClose.addEventListener('click', function() {
      mobileMenuToggle.setAttribute('aria-expanded', 'false');
      mobileMenuContainer.classList.remove('block');
      mobileMenuContainer.classList.add('hidden');
      
      // Restore focus to menu toggle
      mobileMenuToggle.focus();
      
      // Allow body scrolling
      document.body.classList.remove('overflow-hidden');
    });
  }
  
  // Close mobile menu with Escape key
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && mobileMenuToggle.getAttribute('aria-expanded') === 'true') {
      mobileMenuToggle.setAttribute('aria-expanded', 'false');
      mobileMenuContainer.classList.remove('block');
      mobileMenuContainer.classList.add('hidden');
      
      // Restore focus to menu toggle
      mobileMenuToggle.focus();
      
      // Allow body scrolling
      document.body.classList.remove('overflow-hidden');
    }
  });
  
  // Get all the link elements within the menu.
  const links = siteNavigation.getElementsByTagName('a');
  
  // Get all dropdown toggles
  const dropdownToggles = siteNavigation.querySelectorAll('.dropdown-toggle');
  
  // Toggle focus each time a menu link is focused or blurred.
  for (const link of links) {
    link.addEventListener('focus', toggleFocus, true);
    link.addEventListener('blur', toggleFocus, true);
  }
  
  // Toggle dropdown menus
  for (const toggle of dropdownToggles) {
    toggle.addEventListener('click', function(event) {
      event.preventDefault();
      
      const parent = this.parentNode;
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      
      // Close all other dropdowns
      for (const otherToggle of dropdownToggles) {
        if (otherToggle !== this) {
          otherToggle.setAttribute('aria-expanded', 'false');
          otherToggle.nextElementSibling.classList.add('hidden');
        }
      }
      
      // Toggle current dropdown
      this.setAttribute('aria-expanded', !isExpanded);
      this.nextElementSibling.classList.toggle('hidden');
    });
  }
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', function(event) {
    let isClickInside = siteNavigation.contains(event.target);
    
    if (!isClickInside) {
      for (const toggle of dropdownToggles) {
        toggle.setAttribute('aria-expanded', 'false');
        toggle.nextElementSibling.classList.add('hidden');
      }
    }
  });
  
  /**
   * Sets or removes .focus class on an element.
   */
  function toggleFocus() {
    if (event.type === 'focus' || event.type === 'blur') {
      let self = this;
      
      // Move up through the ancestors of the current link until we hit .nav-menu.
      while (!self.classList.contains('nav-menu')) {
        // On li elements toggle the class .focus.
        if ('li' === self.tagName.toLowerCase()) {
          self.classList.toggle('focus', event.type === 'focus');
        }
        self = self.parentNode;
      }
    }
  }
  
  /**
   * Trap focus in modal/menu
   */
  function trapFocus(element) {
    const focusableElements = element.querySelectorAll(
      'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
    );
    
    const firstFocusableElement = focusableElements[0];
    const lastFocusableElement = focusableElements[focusableElements.length - 1];
    
    // Set focus on first element
    firstFocusableElement.focus();
    
    element.addEventListener('keydown', function(event) {
      if (event.key === 'Tab') {
        // Shift + Tab
        if (event.shiftKey) {
          if (document.activeElement === firstFocusableElement) {
            lastFocusableElement.focus();
            event.preventDefault();
          }
        } 
        // Tab
        else {
          if (document.activeElement === lastFocusableElement) {
            firstFocusableElement.focus();
            event.preventDefault();
          }
        }
      }
    });
  }
});