/**
 * AquaLuxe Theme - Keyboard Navigation
 *
 * This file enhances keyboard navigation accessibility throughout the theme.
 */

(function() {
  'use strict';

  /**
   * Initialize keyboard navigation
   */
  function init() {
    initFocusVisible();
    initDropdownKeyboardNav();
    initModalKeyboardNav();
    initTabKeyboardNav();
    initSkipLinkFocus();
    initFormFieldFocus();
  }

  /**
   * Initialize focus-visible polyfill
   * This helps distinguish between mouse and keyboard focus
   */
  function initFocusVisible() {
    // Check if the browser supports :focus-visible
    const focusVisibleSupported = CSS.supports('selector(:focus-visible)');
    
    if (focusVisibleSupported) {
      // Add class to html element for CSS targeting
      document.documentElement.classList.add('focus-visible-supported');
      return;
    }
    
    // Add polyfill for browsers that don't support :focus-visible
    document.documentElement.classList.add('js-focus-visible');
    
    // Track current input method (keyboard vs mouse)
    let usingKeyboard = false;
    
    // Add event listeners to track input method
    document.addEventListener('keydown', function() {
      usingKeyboard = true;
    });
    
    document.addEventListener('mousedown', function() {
      usingKeyboard = false;
    });
    
    document.addEventListener('focusin', function(event) {
      if (usingKeyboard) {
        event.target.classList.add('focus-visible');
      }
    });
    
    document.addEventListener('focusout', function(event) {
      event.target.classList.remove('focus-visible');
    });
  }

  /**
   * Initialize dropdown keyboard navigation
   */
  function initDropdownKeyboardNav() {
    const menuItems = document.querySelectorAll('.menu-item-has-children > a');
    
    menuItems.forEach(function(menuItem) {
      // Handle keyboard navigation
      menuItem.addEventListener('keydown', function(event) {
        const parentItem = this.parentNode;
        const submenu = parentItem.querySelector('.sub-menu');
        
        if (!submenu) {
          return;
        }
        
        // Open submenu on Enter, Space, or Arrow Down
        if (event.key === 'Enter' || event.key === ' ' || event.key === 'ArrowDown') {
          event.preventDefault();
          
          // Toggle aria-expanded
          const isExpanded = this.getAttribute('aria-expanded') === 'true';
          this.setAttribute('aria-expanded', !isExpanded);
          
          // Toggle submenu visibility
          if (!isExpanded) {
            submenu.style.display = 'block';
            
            // Focus first submenu item
            const firstItem = submenu.querySelector('a');
            if (firstItem) {
              setTimeout(function() {
                firstItem.focus();
              }, 10);
            }
          } else {
            submenu.style.display = '';
          }
        }
      });
    });
    
    // Handle keyboard navigation within submenus
    const submenuItems = document.querySelectorAll('.sub-menu a');
    
    submenuItems.forEach(function(item) {
      item.addEventListener('keydown', function(event) {
        const parentSubmenu = this.closest('.sub-menu');
        const parentMenuItem = parentSubmenu.parentNode;
        const parentMenuLink = parentMenuItem.querySelector('a');
        const items = Array.from(parentSubmenu.querySelectorAll('a'));
        const index = items.indexOf(this);
        
        // Handle keyboard navigation
        switch (event.key) {
          case 'ArrowUp':
            event.preventDefault();
            
            // Focus previous item or parent menu item
            if (index > 0) {
              items[index - 1].focus();
            } else {
              parentMenuLink.focus();
              parentMenuLink.setAttribute('aria-expanded', 'false');
              parentSubmenu.style.display = '';
            }
            break;
            
          case 'ArrowDown':
            event.preventDefault();
            
            // Focus next item
            if (index < items.length - 1) {
              items[index + 1].focus();
            }
            break;
            
          case 'Escape':
            event.preventDefault();
            
            // Close submenu and focus parent menu item
            parentMenuLink.focus();
            parentMenuLink.setAttribute('aria-expanded', 'false');
            parentSubmenu.style.display = '';
            break;
        }
      });
    });
  }

  /**
   * Initialize modal keyboard navigation
   */
  function initModalKeyboardNav() {
    // Get all modals
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(function(modal) {
      // Trap focus inside modal when open
      modal.addEventListener('keydown', function(event) {
        // Only trap focus if modal is active
        if (!modal.classList.contains('is-active')) {
          return;
        }
        
        // Get all focusable elements in modal
        const focusableElements = modal.querySelectorAll(
          'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) {
          return;
        }
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        // Handle Tab key to trap focus
        if (event.key === 'Tab') {
          if (event.shiftKey && document.activeElement === firstElement) {
            // Shift+Tab on first element - focus last element
            event.preventDefault();
            lastElement.focus();
          } else if (!event.shiftKey && document.activeElement === lastElement) {
            // Tab on last element - focus first element
            event.preventDefault();
            firstElement.focus();
          }
        }
        
        // Close modal on Escape
        if (event.key === 'Escape') {
          const closeButton = modal.querySelector('.modal__close');
          if (closeButton) {
            closeButton.click();
          }
        }
      });
      
      // Store last focused element before modal opens
      const modalTriggers = document.querySelectorAll('[data-modal-target="' + modal.id + '"]');
      
      modalTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
          modal.previouslyFocused = document.activeElement;
        });
      });
      
      // Restore focus when modal closes
      const closeButtons = modal.querySelectorAll('[data-modal-close]');
      
      closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
          if (modal.previouslyFocused) {
            setTimeout(function() {
              modal.previouslyFocused.focus();
            }, 10);
          }
        });
      });
    });
  }

  /**
   * Initialize tab keyboard navigation
   */
  function initTabKeyboardNav() {
    // Get all tab containers
    const tabContainers = document.querySelectorAll('.tabs');
    
    tabContainers.forEach(function(container) {
      const tabList = container.querySelector('.tabs-list');
      const tabButtons = container.querySelectorAll('.tabs-button');
      
      if (!tabList || !tabButtons.length) {
        return;
      }
      
      // Set ARIA roles
      tabList.setAttribute('role', 'tablist');
      
      tabButtons.forEach(function(button, index) {
        // Set ARIA roles and attributes
        button.setAttribute('role', 'tab');
        button.setAttribute('id', 'tab-' + index);
        button.setAttribute('aria-selected', button.classList.contains('is-active') ? 'true' : 'false');
        button.setAttribute('tabindex', button.classList.contains('is-active') ? '0' : '-1');
        
        const panel = document.getElementById(button.getAttribute('aria-controls'));
        if (panel) {
          panel.setAttribute('role', 'tabpanel');
          panel.setAttribute('aria-labelledby', 'tab-' + index);
          panel.setAttribute('tabindex', '0');
        }
        
        // Handle keyboard navigation
        button.addEventListener('keydown', function(event) {
          const buttons = Array.from(tabButtons);
          const index = buttons.indexOf(button);
          let targetButton;
          
          switch (event.key) {
            case 'ArrowLeft':
              event.preventDefault();
              targetButton = buttons[index - 1] || buttons[buttons.length - 1];
              break;
              
            case 'ArrowRight':
              event.preventDefault();
              targetButton = buttons[index + 1] || buttons[0];
              break;
              
            case 'Home':
              event.preventDefault();
              targetButton = buttons[0];
              break;
              
            case 'End':
              event.preventDefault();
              targetButton = buttons[buttons.length - 1];
              break;
              
            default:
              return;
          }
          
          // Focus and activate target button
          targetButton.focus();
          targetButton.click();
        });
      });
    });
  }

  /**
   * Initialize skip link focus
   */
  function initSkipLinkFocus() {
    // Fix skip link focus in Chrome
    window.addEventListener('hashchange', function() {
      if (location.hash.substring(1).startsWith('skip-')) {
        const element = document.getElementById(location.hash.substring(1));
        
        if (element) {
          element.setAttribute('tabindex', '-1');
          element.focus();
        }
      }
    });
    
    // Handle skip link click
    const skipLink = document.querySelector('.skip-link');
    
    if (skipLink) {
      skipLink.addEventListener('click', function(event) {
        // Get target from href
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        
        if (target) {
          event.preventDefault();
          target.setAttribute('tabindex', '-1');
          target.focus();
        }
      });
    }
  }

  /**
   * Initialize form field focus
   */
  function initFormFieldFocus() {
    // Add focus and blur handlers for form fields
    const formFields = document.querySelectorAll('input, textarea, select');
    
    formFields.forEach(function(field) {
      // Add focused class to parent on focus
      field.addEventListener('focus', function() {
        const formGroup = this.closest('.form-group');
        if (formGroup) {
          formGroup.classList.add('is-focused');
        }
      });
      
      // Remove focused class from parent on blur
      field.addEventListener('blur', function() {
        const formGroup = this.closest('.form-group');
        if (formGroup) {
          formGroup.classList.remove('is-focused');
        }
      });
    });
    
    // Add error message announcement for screen readers
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
      form.addEventListener('submit', function(event) {
        // Check for invalid fields
        const invalidFields = form.querySelectorAll(':invalid');
        
        if (invalidFields.length) {
          // Create or update error summary
          let errorSummary = form.querySelector('.error-summary');
          
          if (!errorSummary) {
            errorSummary = document.createElement('div');
            errorSummary.className = 'error-summary';
            errorSummary.setAttribute('role', 'alert');
            errorSummary.setAttribute('aria-live', 'assertive');
            form.prepend(errorSummary);
          }
          
          // Update error summary content
          errorSummary.innerHTML = '<p>' + invalidFields.length + ' form ' + (invalidFields.length === 1 ? 'field has' : 'fields have') + ' errors. Please correct them and try again.</p>';
          
          // Focus first invalid field
          invalidFields[0].focus();
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