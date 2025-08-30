/**
 * AquaLuxe WordPress Theme
 * Accessibility Module
 */

/**
 * File accessibility.js.
 *
 * Handles keyboard navigation for accessibility.
 */
(function() {
  /**
   * Skip Link Focus Fix
   *
   * Helps with accessibility for keyboard only users.
   *
   * Learn more: https://git.io/vWdr2
   */
  const isIe = /(trident|msie)/i.test(navigator.userAgent);

  if (isIe && document.getElementById && window.addEventListener) {
    window.addEventListener('hashchange', function() {
      const id = location.hash.substring(1);

      if (!(/^[A-z0-9_-]+$/.test(id))) {
        return;
      }

      const element = document.getElementById(id);

      if (element) {
        if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
          element.tabIndex = -1;
        }

        element.focus();
      }
    }, false);
  }

  /**
   * Trap keyboard navigation in modals
   */
  const modals = document.querySelectorAll('.modal');
  
  modals.forEach(modal => {
    const focusableElements = modal.querySelectorAll('a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
    const firstFocusableElement = focusableElements[0];
    const lastFocusableElement = focusableElements[focusableElements.length - 1];
    
    modal.addEventListener('keydown', function(e) {
      if (e.key === 'Tab' || e.keyCode === 9) {
        if (e.shiftKey) {
          // If shift key pressed for shift + tab combination
          if (document.activeElement === firstFocusableElement) {
            lastFocusableElement.focus(); // Add focus for the last focusable element
            e.preventDefault();
          }
        } else {
          // If tab key is pressed
          if (document.activeElement === lastFocusableElement) {
            firstFocusableElement.focus(); // Add focus for the first focusable element
            e.preventDefault();
          }
        }
      }
      
      // Close modal on escape key
      if (e.key === 'Escape' || e.keyCode === 27) {
        modal.classList.remove('is-active');
        document.body.classList.remove('modal-open');
      }
    });
  });

  /**
   * Add aria-expanded attribute to dropdown menus
   */
  const menuItems = document.querySelectorAll('.menu-item-has-children, .page_item_has_children');
  
  menuItems.forEach(item => {
    const link = item.querySelector('a');
    const subMenu = item.querySelector('.sub-menu, .children');
    
    if (link && subMenu) {
      link.setAttribute('aria-expanded', 'false');
      
      link.addEventListener('click', function() {
        const expanded = this.getAttribute('aria-expanded') === 'true' || false;
        this.setAttribute('aria-expanded', !expanded);
      });
    }
  });

  /**
   * Enhance keyboard navigation for dropdown menus
   */
  const navLinks = document.querySelectorAll('.nav-menu a');
  
  navLinks.forEach(link => {
    link.addEventListener('keydown', function(e) {
      const parentMenuItem = link.parentNode;
      
      // Open submenu on arrow down/right
      if ((e.key === 'ArrowDown' || e.key === 'ArrowRight' || e.keyCode === 40 || e.keyCode === 39) && 
          parentMenuItem.classList.contains('menu-item-has-children')) {
        e.preventDefault();
        parentMenuItem.classList.add('focus');
        link.setAttribute('aria-expanded', 'true');
        
        const subMenu = parentMenuItem.querySelector('.sub-menu');
        if (subMenu) {
          const firstSubLink = subMenu.querySelector('a');
          if (firstSubLink) {
            firstSubLink.focus();
          }
        }
      }
      
      // Close submenu on arrow up/left
      if ((e.key === 'ArrowUp' || e.key === 'ArrowLeft' || e.keyCode === 38 || e.keyCode === 37) && 
          parentMenuItem.parentNode.classList.contains('sub-menu')) {
        e.preventDefault();
        const parentMenuItemParent = parentMenuItem.parentNode.parentNode;
        parentMenuItemParent.classList.remove('focus');
        
        const parentLink = parentMenuItemParent.querySelector('a');
        if (parentLink) {
          parentLink.setAttribute('aria-expanded', 'false');
          parentLink.focus();
        }
      }
      
      // Close submenu on escape
      if ((e.key === 'Escape' || e.keyCode === 27) && 
          parentMenuItem.parentNode.classList.contains('sub-menu')) {
        e.preventDefault();
        const parentMenuItemParent = parentMenuItem.parentNode.parentNode;
        parentMenuItemParent.classList.remove('focus');
        
        const parentLink = parentMenuItemParent.querySelector('a');
        if (parentLink) {
          parentLink.setAttribute('aria-expanded', 'false');
          parentLink.focus();
        }
      }
    });
  });

  /**
   * Add aria-current attribute to current menu item
   */
  const currentMenuItems = document.querySelectorAll('.current-menu-item, .current_page_item');
  
  currentMenuItems.forEach(item => {
    const link = item.querySelector('a');
    if (link) {
      link.setAttribute('aria-current', 'page');
    }
  });

  /**
   * Add focus styles for form fields
   */
  const formFields = document.querySelectorAll('input, textarea, select');
  
  formFields.forEach(field => {
    field.addEventListener('focus', function() {
      this.parentNode.classList.add('is-focused');
    });
    
    field.addEventListener('blur', function() {
      this.parentNode.classList.remove('is-focused');
    });
  });

  /**
   * Implement "Back to top" button functionality
   */
  const backToTopButton = document.querySelector('.back-to-top');
  
  if (backToTopButton) {
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.add('is-visible');
      } else {
        backToTopButton.classList.remove('is-visible');
      }
    });
    
    // Scroll to top when clicked
    backToTopButton.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  /**
   * Add ARIA attributes to search form
   */
  const searchForms = document.querySelectorAll('.search-form');
  
  searchForms.forEach(form => {
    const searchInput = form.querySelector('.search-field');
    const searchSubmit = form.querySelector('.search-submit');
    
    if (searchInput && searchSubmit) {
      const searchId = 'search-' + Math.floor(Math.random() * 1000);
      
      searchInput.setAttribute('id', searchId);
      searchInput.setAttribute('aria-label', 'Search');
      
      searchSubmit.setAttribute('aria-label', 'Submit search');
    }
  });

  /**
   * Add ARIA attributes to social navigation
   */
  const socialNav = document.querySelector('.social-navigation');
  
  if (socialNav) {
    const socialLinks = socialNav.querySelectorAll('a');
    
    socialLinks.forEach(link => {
      // Extract social network name from classes or href
      let socialNetwork = '';
      
      if (link.classList.contains('social-icon')) {
        const classes = Array.from(link.classList);
        const socialClass = classes.find(className => className.startsWith('social-'));
        
        if (socialClass) {
          socialNetwork = socialClass.replace('social-', '');
        }
      }
      
      if (!socialNetwork) {
        const href = link.getAttribute('href') || '';
        
        if (href.includes('facebook.com')) {
          socialNetwork = 'Facebook';
        } else if (href.includes('twitter.com')) {
          socialNetwork = 'Twitter';
        } else if (href.includes('instagram.com')) {
          socialNetwork = 'Instagram';
        } else if (href.includes('linkedin.com')) {
          socialNetwork = 'LinkedIn';
        } else if (href.includes('youtube.com')) {
          socialNetwork = 'YouTube';
        } else if (href.includes('pinterest.com')) {
          socialNetwork = 'Pinterest';
        }
      }
      
      if (socialNetwork) {
        link.setAttribute('aria-label', socialNetwork);
      }
    });
  }
})();