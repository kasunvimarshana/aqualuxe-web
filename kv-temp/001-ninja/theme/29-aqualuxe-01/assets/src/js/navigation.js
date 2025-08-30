/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function() {
  const siteNavigation = document.getElementById('site-navigation');
  const mobileToggle = document.getElementById('mobile-menu-toggle');
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
  // Return early if the navigation doesn't exist.
  if (!siteNavigation || !mobileToggle) {
    return;
  }

  // Toggle mobile menu
  mobileToggle.addEventListener('click', function() {
    siteNavigation.classList.toggle('active');
    
    if (siteNavigation.classList.contains('active')) {
      mobileToggle.setAttribute('aria-expanded', 'true');
    } else {
      mobileToggle.setAttribute('aria-expanded', 'false');
    }
  });

  // Handle dropdown menus
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      const parent = this.parentNode;
      const dropdown = parent.querySelector('.dropdown-menu');
      
      // Close all other dropdowns
      document.querySelectorAll('.dropdown-menu.active').forEach(menu => {
        if (menu !== dropdown) {
          menu.classList.remove('active');
          menu.parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false');
        }
      });
      
      // Toggle current dropdown
      dropdown.classList.toggle('active');
      
      if (dropdown.classList.contains('active')) {
        this.setAttribute('aria-expanded', 'true');
      } else {
        this.setAttribute('aria-expanded', 'false');
      }
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.menu-item-has-children')) {
      document.querySelectorAll('.dropdown-menu.active').forEach(menu => {
        menu.classList.remove('active');
        menu.parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false');
      });
    }
  });

  // Handle keyboard navigation
  document.addEventListener('keydown', function(e) {
    // ESC key closes all dropdowns
    if (e.key === 'Escape') {
      document.querySelectorAll('.dropdown-menu.active').forEach(menu => {
        menu.classList.remove('active');
        menu.parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false');
      });
    }
    
    // Tab key navigation for dropdowns
    if (e.key === 'Tab') {
      const activeDropdown = document.querySelector('.dropdown-menu.active');
      if (activeDropdown) {
        const focusableElements = activeDropdown.querySelectorAll('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
        
        if (focusableElements.length > 0) {
          const firstElement = focusableElements[0];
          const lastElement = focusableElements[focusableElements.length - 1];
          
          // If shift+tab on first element, close dropdown and focus on toggle
          if (e.shiftKey && document.activeElement === firstElement) {
            e.preventDefault();
            activeDropdown.classList.remove('active');
            activeDropdown.parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false');
            activeDropdown.parentNode.querySelector('.dropdown-toggle').focus();
          }
          
          // If tab on last element, close dropdown
          if (!e.shiftKey && document.activeElement === lastElement) {
            activeDropdown.classList.remove('active');
            activeDropdown.parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false');
          }
        }
      }
    }
  });

  // Handle responsive behavior
  const handleResize = () => {
    if (window.innerWidth >= 1024) { // Desktop breakpoint
      siteNavigation.classList.remove('active');
      mobileToggle.setAttribute('aria-expanded', 'false');
    }
  };

  window.addEventListener('resize', handleResize);
  handleResize(); // Initial check
})();