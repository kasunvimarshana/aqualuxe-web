/**
 * AquaLuxe Theme - Accessibility Enhancements
 *
 * This file provides additional accessibility features beyond keyboard navigation.
 */

(function() {
  'use strict';

  /**
   * Initialize accessibility enhancements
   */
  function init() {
    initAriaAttributes();
    initScreenReaderAnnouncements();
    initColorContrastToggle();
    initFocusManagement();
    initAccessibleTooltips();
    initAccessibleAccordions();
  }

  /**
   * Initialize ARIA attributes throughout the site
   */
  function initAriaAttributes() {
    // Add missing ARIA attributes to navigation menus
    const navMenus = document.querySelectorAll('.menu, .sub-menu');
    navMenus.forEach(function(menu) {
      if (!menu.getAttribute('role')) {
        menu.setAttribute('role', 'menu');
      }
      
      const menuItems = menu.querySelectorAll('li');
      menuItems.forEach(function(item) {
        if (!item.getAttribute('role')) {
          item.setAttribute('role', 'menuitem');
        }
        
        // If menu item has children, set it as a menuitemwithpopup
        if (item.classList.contains('menu-item-has-children')) {
          const link = item.querySelector('a');
          if (link && !link.getAttribute('aria-haspopup')) {
            link.setAttribute('aria-haspopup', 'true');
          }
          if (link && !link.getAttribute('aria-expanded')) {
            link.setAttribute('aria-expanded', 'false');
          }
        }
      });
    });
    
    // Add ARIA attributes to search forms
    const searchForms = document.querySelectorAll('.search-form');
    searchForms.forEach(function(form) {
      if (!form.getAttribute('role')) {
        form.setAttribute('role', 'search');
      }
    });
    
    // Add ARIA attributes to product filters
    const productFilters = document.querySelectorAll('.woocommerce-ordering, .woocommerce-result-count');
    productFilters.forEach(function(filter) {
      if (!filter.getAttribute('aria-live')) {
        filter.setAttribute('aria-live', 'polite');
      }
    });
    
    // Add ARIA attributes to carousels
    const carousels = document.querySelectorAll('.carousel, .slider');
    carousels.forEach(function(carousel) {
      if (!carousel.getAttribute('role')) {
        carousel.setAttribute('role', 'region');
      }
      if (!carousel.getAttribute('aria-roledescription')) {
        carousel.setAttribute('aria-roledescription', 'carousel');
      }
      
      // Add ARIA to carousel controls
      const prevButton = carousel.querySelector('.prev, .slick-prev');
      const nextButton = carousel.querySelector('.next, .slick-next');
      
      if (prevButton && !prevButton.getAttribute('aria-label')) {
        prevButton.setAttribute('aria-label', 'Previous slide');
      }
      
      if (nextButton && !nextButton.getAttribute('aria-label')) {
        nextButton.setAttribute('aria-label', 'Next slide');
      }
      
      // Add ARIA to carousel slides
      const slides = carousel.querySelectorAll('.slide, .slick-slide');
      slides.forEach(function(slide, index) {
        if (!slide.getAttribute('role')) {
          slide.setAttribute('role', 'group');
        }
        if (!slide.getAttribute('aria-roledescription')) {
          slide.setAttribute('aria-roledescription', 'slide');
        }
        if (!slide.getAttribute('aria-label')) {
          slide.setAttribute('aria-label', 'Slide ' + (index + 1));
        }
      });
    });
    
    // Add ARIA attributes to product galleries
    const productGalleries = document.querySelectorAll('.woocommerce-product-gallery');
    productGalleries.forEach(function(gallery) {
      if (!gallery.getAttribute('role')) {
        gallery.setAttribute('role', 'region');
      }
      if (!gallery.getAttribute('aria-roledescription')) {
        gallery.setAttribute('aria-roledescription', 'product gallery');
      }
      
      // Add ARIA to gallery thumbnails
      const thumbnails = gallery.querySelectorAll('.woocommerce-product-gallery__image');
      thumbnails.forEach(function(thumbnail, index) {
        const link = thumbnail.querySelector('a');
        if (link && !link.getAttribute('aria-label')) {
          link.setAttribute('aria-label', 'Product image ' + (index + 1));
        }
      });
    });
  }

  /**
   * Initialize screen reader announcements for dynamic content
   */
  function initScreenReaderAnnouncements() {
    // Create screen reader announcement area if it doesn't exist
    let srAnnounce = document.getElementById('sr-announce');
    if (!srAnnounce) {
      srAnnounce = document.createElement('div');
      srAnnounce.id = 'sr-announce';
      srAnnounce.className = 'sr-only';
      srAnnounce.setAttribute('aria-live', 'polite');
      srAnnounce.setAttribute('aria-atomic', 'true');
      document.body.appendChild(srAnnounce);
    }
    
    // Add announcement function to window object for global access
    window.announceToScreenReader = function(message) {
      srAnnounce.textContent = '';
      // Use setTimeout to ensure the DOM update is recognized by screen readers
      setTimeout(function() {
        srAnnounce.textContent = message;
      }, 100);
    };
    
    // Add announcements for AJAX cart updates
    if (typeof jQuery !== 'undefined') {
      jQuery(document.body).on('added_to_cart', function() {
        window.announceToScreenReader('Product added to cart');
      });
      
      jQuery(document.body).on('removed_from_cart', function() {
        window.announceToScreenReader('Product removed from cart');
      });
      
      jQuery(document.body).on('updated_cart_totals', function() {
        window.announceToScreenReader('Cart updated');
      });
      
      jQuery(document.body).on('updated_checkout', function() {
        window.announceToScreenReader('Checkout information updated');
      });
    }
  }

  /**
   * Initialize high contrast mode toggle
   */
  function initColorContrastToggle() {
    // Create high contrast toggle button
    const contrastButton = document.createElement('button');
    contrastButton.id = 'contrast-toggle';
    contrastButton.className = 'contrast-toggle';
    contrastButton.setAttribute('aria-pressed', 'false');
    contrastButton.setAttribute('aria-label', 'Toggle high contrast mode');
    
    // Create icon for the button
    const contrastIcon = document.createElement('span');
    contrastIcon.className = 'contrast-icon';
    contrastIcon.setAttribute('aria-hidden', 'true');
    contrastIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a10 10 0 0 1 0 20z"></path></svg>';
    
    // Create text for screen readers
    const contrastText = document.createElement('span');
    contrastText.className = 'sr-only';
    contrastText.textContent = 'Toggle high contrast';
    
    // Assemble button
    contrastButton.appendChild(contrastIcon);
    contrastButton.appendChild(contrastText);
    
    // Add button to the accessibility toolbar or create one if it doesn't exist
    let accessibilityToolbar = document.querySelector('.accessibility-toolbar');
    if (!accessibilityToolbar) {
      accessibilityToolbar = document.createElement('div');
      accessibilityToolbar.className = 'accessibility-toolbar fixed right-4 top-1/2 transform -translate-y-1/2 flex flex-col space-y-2 z-50';
      document.body.appendChild(accessibilityToolbar);
    }
    
    accessibilityToolbar.appendChild(contrastButton);
    
    // Add event listener to toggle high contrast mode
    contrastButton.addEventListener('click', function() {
      const isHighContrast = document.documentElement.classList.toggle('high-contrast');
      this.setAttribute('aria-pressed', isHighContrast);
      
      // Save preference to localStorage
      localStorage.setItem('aqualuxe-high-contrast', isHighContrast ? 'true' : 'false');
      
      // Announce change to screen readers
      window.announceToScreenReader(isHighContrast ? 'High contrast mode enabled' : 'High contrast mode disabled');
    });
    
    // Check for saved preference
    if (localStorage.getItem('aqualuxe-high-contrast') === 'true') {
      document.documentElement.classList.add('high-contrast');
      contrastButton.setAttribute('aria-pressed', 'true');
    }
  }

  /**
   * Initialize better focus management for interactive elements
   */
  function initFocusManagement() {
    // Add focus indicator to all interactive elements
    const interactiveElements = document.querySelectorAll('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
    interactiveElements.forEach(function(element) {
      if (!element.classList.contains('focus-visible-enabled')) {
        element.classList.add('focus-visible-enabled');
      }
    });
    
    // Manage focus for dynamic content
    const observeDOM = (function() {
      const MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
      
      return function(obj, callback) {
        if (!obj || obj.nodeType !== 1) return;
        
        if (MutationObserver) {
          // Define a new observer
          const mutationObserver = new MutationObserver(callback);
          
          // Have the observer observe for changes in children
          mutationObserver.observe(obj, { childList: true, subtree: true });
          return mutationObserver;
        } else if (window.addEventListener) {
          // Browser support fallback
          obj.addEventListener('DOMNodeInserted', callback, false);
          obj.addEventListener('DOMNodeRemoved', callback, false);
        }
      };
    })();
    
    // Observe DOM changes to add focus management to new elements
    observeDOM(document.body, function() {
      const newInteractiveElements = document.querySelectorAll('a:not(.focus-visible-enabled), button:not(.focus-visible-enabled), input:not(.focus-visible-enabled), select:not(.focus-visible-enabled), textarea:not(.focus-visible-enabled), [tabindex]:not([tabindex="-1"]):not(.focus-visible-enabled)');
      newInteractiveElements.forEach(function(element) {
        element.classList.add('focus-visible-enabled');
      });
    });
    
    // Add focus trap for modals
    const modals = document.querySelectorAll('.modal, [role="dialog"]');
    modals.forEach(function(modal) {
      // This is handled in keyboard-navigation.js
    });
  }

  /**
   * Initialize accessible tooltips
   */
  function initAccessibleTooltips() {
    // Find all elements with tooltip attributes
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(function(trigger) {
      const tooltipText = trigger.getAttribute('data-tooltip');
      const tooltipPosition = trigger.getAttribute('data-tooltip-position') || 'top';
      
      // Create tooltip element
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltip tooltip-' + tooltipPosition;
      tooltip.setAttribute('role', 'tooltip');
      tooltip.textContent = tooltipText;
      
      // Set unique ID for ARIA relationship
      const tooltipId = 'tooltip-' + Math.random().toString(36).substr(2, 9);
      tooltip.id = tooltipId;
      
      // Add ARIA attributes to trigger
      trigger.setAttribute('aria-describedby', tooltipId);
      
      // Add event listeners
      trigger.addEventListener('mouseenter', function() {
        document.body.appendChild(tooltip);
        positionTooltip(trigger, tooltip, tooltipPosition);
      });
      
      trigger.addEventListener('mouseleave', function() {
        if (document.body.contains(tooltip)) {
          document.body.removeChild(tooltip);
        }
      });
      
      trigger.addEventListener('focus', function() {
        document.body.appendChild(tooltip);
        positionTooltip(trigger, tooltip, tooltipPosition);
      });
      
      trigger.addEventListener('blur', function() {
        if (document.body.contains(tooltip)) {
          document.body.removeChild(tooltip);
        }
      });
    });
    
    // Position tooltip relative to trigger
    function positionTooltip(trigger, tooltip, position) {
      const triggerRect = trigger.getBoundingClientRect();
      const tooltipRect = tooltip.getBoundingClientRect();
      
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
      
      let top, left;
      
      switch (position) {
        case 'top':
          top = triggerRect.top + scrollTop - tooltipRect.height - 10;
          left = triggerRect.left + scrollLeft + (triggerRect.width / 2) - (tooltipRect.width / 2);
          break;
        case 'bottom':
          top = triggerRect.bottom + scrollTop + 10;
          left = triggerRect.left + scrollLeft + (triggerRect.width / 2) - (tooltipRect.width / 2);
          break;
        case 'left':
          top = triggerRect.top + scrollTop + (triggerRect.height / 2) - (tooltipRect.height / 2);
          left = triggerRect.left + scrollLeft - tooltipRect.width - 10;
          break;
        case 'right':
          top = triggerRect.top + scrollTop + (triggerRect.height / 2) - (tooltipRect.height / 2);
          left = triggerRect.right + scrollLeft + 10;
          break;
      }
      
      // Ensure tooltip stays within viewport
      if (left < 0) left = 0;
      if (top < 0) top = 0;
      if (left + tooltipRect.width > window.innerWidth) {
        left = window.innerWidth - tooltipRect.width;
      }
      if (top + tooltipRect.height > window.innerHeight + scrollTop) {
        top = triggerRect.top + scrollTop - tooltipRect.height - 10;
      }
      
      tooltip.style.top = top + 'px';
      tooltip.style.left = left + 'px';
    }
  }

  /**
   * Initialize accessible accordions
   */
  function initAccessibleAccordions() {
    // Find all accordion elements
    const accordions = document.querySelectorAll('.accordion');
    
    accordions.forEach(function(accordion) {
      const headers = accordion.querySelectorAll('.accordion-header');
      
      headers.forEach(function(header, index) {
        // Set ARIA attributes
        if (!header.getAttribute('role')) {
          header.setAttribute('role', 'button');
        }
        if (!header.getAttribute('aria-expanded')) {
          header.setAttribute('aria-expanded', 'false');
        }
        
        // Create unique ID for the panel
        const panelId = 'accordion-panel-' + index;
        header.setAttribute('aria-controls', panelId);
        
        // Find the panel
        const panel = header.nextElementSibling;
        if (panel && panel.classList.contains('accordion-panel')) {
          panel.id = panelId;
          panel.setAttribute('role', 'region');
          panel.setAttribute('aria-labelledby', header.id || 'accordion-header-' + index);
          
          if (!header.id) {
            header.id = 'accordion-header-' + index;
          }
          
          // Hide panel initially if not expanded
          if (header.getAttribute('aria-expanded') === 'false') {
            panel.hidden = true;
          }
        }
        
        // Add click event
        header.addEventListener('click', function() {
          const isExpanded = this.getAttribute('aria-expanded') === 'true';
          this.setAttribute('aria-expanded', !isExpanded);
          
          // Toggle panel visibility
          if (panel) {
            panel.hidden = isExpanded;
          }
        });
        
        // Add keyboard support
        header.addEventListener('keydown', function(event) {
          if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            this.click();
          }
        });
        
        // Ensure header is focusable
        if (!header.getAttribute('tabindex')) {
          header.setAttribute('tabindex', '0');
        }
      });
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();