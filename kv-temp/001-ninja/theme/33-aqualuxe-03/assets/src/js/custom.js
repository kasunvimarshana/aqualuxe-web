/**
 * AquaLuxe Theme - Custom Scripts
 *
 * This file contains custom JavaScript functionality for the theme including:
 * - Smooth scrolling
 * - Modal handling
 * - Tabs
 * - Accordions
 * - Tooltips
 * - Form validation
 */

(function() {
  'use strict';
  
  /**
   * Initialize all custom functionality
   */
  function init() {
    initSmoothScroll();
    initModals();
    initTabs();
    initAccordions();
    initTooltips();
    initFormValidation();
    initAnimations();
    initBackToTop();
    initSearchToggle();
    initQuantityControls();
  }
  
  /**
   * Initialize smooth scrolling for anchor links
   */
  function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]:not([href="#"])');
    
    anchorLinks.forEach(function(link) {
      link.addEventListener('click', function(event) {
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
          event.preventDefault();
          
          const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
          const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
          const offsetPosition = targetPosition - headerHeight - 20; // 20px extra padding
          
          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });
          
          // Update URL hash without scrolling
          history.pushState(null, null, targetId);
          
          // Set focus to target element
          targetElement.setAttribute('tabindex', '-1');
          targetElement.focus({ preventScroll: true });
        }
      });
    });
  }
  
  /**
   * Initialize modal functionality
   */
  function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const modalCloseButtons = document.querySelectorAll('[data-modal-close]');
    const modals = document.querySelectorAll('.modal');
    
    // Open modal when trigger is clicked
    modalTriggers.forEach(function(trigger) {
      trigger.addEventListener('click', function(event) {
        event.preventDefault();
        
        const modalId = this.getAttribute('data-modal-target');
        const modal = document.getElementById(modalId);
        
        if (modal) {
          openModal(modal);
        }
      });
    });
    
    // Close modal when close button is clicked
    modalCloseButtons.forEach(function(button) {
      button.addEventListener('click', function(event) {
        event.preventDefault();
        
        const modal = this.closest('.modal');
        
        if (modal) {
          closeModal(modal);
        }
      });
    });
    
    // Close modal when clicking outside content
    modals.forEach(function(modal) {
      modal.addEventListener('click', function(event) {
        if (event.target === this) {
          closeModal(modal);
        }
      });
    });
    
    // Close modal when pressing Escape
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        const openModal = document.querySelector('.modal.is-active');
        if (openModal) {
          closeModal(openModal);
        }
      }
    });
    
    /**
     * Open a modal
     * @param {Element} modal - The modal element to open
     */
    function openModal(modal) {
      // Store last focused element to restore focus later
      modal.previouslyFocused = document.activeElement;
      
      // Show modal
      modal.classList.add('is-active');
      modal.setAttribute('aria-hidden', 'false');
      
      // Prevent body scrolling
      document.body.classList.add('modal-open');
      
      // Focus first focusable element in modal
      const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
      if (focusableElements.length) {
        focusableElements[0].focus();
      }
      
      // Trap focus inside modal
      modal.addEventListener('keydown', trapFocus);
      
      // Dispatch event
      modal.dispatchEvent(new CustomEvent('modalOpened'));
    }
    
    /**
     * Close a modal
     * @param {Element} modal - The modal element to close
     */
    function closeModal(modal) {
      // Hide modal
      modal.classList.remove('is-active');
      modal.setAttribute('aria-hidden', 'true');
      
      // Allow body scrolling
      document.body.classList.remove('modal-open');
      
      // Restore focus
      if (modal.previouslyFocused) {
        modal.previouslyFocused.focus();
      }
      
      // Remove focus trap
      modal.removeEventListener('keydown', trapFocus);
      
      // Dispatch event
      modal.dispatchEvent(new CustomEvent('modalClosed'));
    }
    
    /**
     * Trap focus inside modal
     * @param {Event} event - The keydown event
     */
    function trapFocus(event) {
      if (event.key !== 'Tab') {
        return;
      }
      
      const modal = event.currentTarget;
      const focusableElements = Array.from(modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'));
      
      if (focusableElements.length === 0) {
        return;
      }
      
      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];
      
      if (event.shiftKey && document.activeElement === firstElement) {
        event.preventDefault();
        lastElement.focus();
      } else if (!event.shiftKey && document.activeElement === lastElement) {
        event.preventDefault();
        firstElement.focus();
      }
    }
  }
  
  /**
   * Initialize tabs functionality
   */
  function initTabs() {
    const tabContainers = document.querySelectorAll('.tabs');
    
    tabContainers.forEach(function(container) {
      const tabList = container.querySelector('.tabs-list');
      const tabButtons = container.querySelectorAll('.tabs-button');
      const tabPanels = container.querySelectorAll('.tabs-panel');
      
      if (!tabList || !tabButtons.length || !tabPanels.length) {
        return;
      }
      
      // Set ARIA roles and attributes
      tabList.setAttribute('role', 'tablist');
      
      tabButtons.forEach(function(button, index) {
        const panel = tabPanels[index];
        
        if (!panel) {
          return;
        }
        
        const id = panel.id || `tab-panel-${index}`;
        panel.id = id;
        
        button.setAttribute('role', 'tab');
        button.setAttribute('aria-controls', id);
        button.setAttribute('id', `tab-button-${index}`);
        button.setAttribute('tabindex', button.classList.contains('is-active') ? '0' : '-1');
        button.setAttribute('aria-selected', button.classList.contains('is-active') ? 'true' : 'false');
        
        panel.setAttribute('role', 'tabpanel');
        panel.setAttribute('aria-labelledby', `tab-button-${index}`);
        panel.setAttribute('tabindex', '0');
        
        if (!button.classList.contains('is-active')) {
          panel.hidden = true;
        }
        
        // Handle click events
        button.addEventListener('click', function() {
          activateTab(button, container);
        });
        
        // Handle keyboard navigation
        button.addEventListener('keydown', function(event) {
          const buttons = Array.from(tabButtons);
          const index = buttons.indexOf(button);
          let targetButton;
          
          switch (event.key) {
            case 'ArrowLeft':
              targetButton = buttons[index - 1] || buttons[buttons.length - 1];
              break;
            case 'ArrowRight':
              targetButton = buttons[index + 1] || buttons[0];
              break;
            case 'Home':
              targetButton = buttons[0];
              break;
            case 'End':
              targetButton = buttons[buttons.length - 1];
              break;
            default:
              return;
          }
          
          event.preventDefault();
          targetButton.focus();
          activateTab(targetButton, container);
        });
      });
    });
    
    /**
     * Activate a tab
     * @param {Element} tab - The tab button to activate
     * @param {Element} container - The tab container
     */
    function activateTab(tab, container) {
      const tabButtons = container.querySelectorAll('.tabs-button');
      const tabPanels = container.querySelectorAll('.tabs-panel');
      const targetPanel = container.querySelector(`#${tab.getAttribute('aria-controls')}`);
      
      // Deactivate all tabs
      tabButtons.forEach(function(button) {
        button.classList.remove('is-active');
        button.setAttribute('aria-selected', 'false');
        button.setAttribute('tabindex', '-1');
      });
      
      // Hide all panels
      tabPanels.forEach(function(panel) {
        panel.hidden = true;
      });
      
      // Activate selected tab
      tab.classList.add('is-active');
      tab.setAttribute('aria-selected', 'true');
      tab.setAttribute('tabindex', '0');
      
      // Show selected panel
      if (targetPanel) {
        targetPanel.hidden = false;
      }
      
      // Dispatch event
      container.dispatchEvent(new CustomEvent('tabChanged', {
        detail: {
          tab: tab,
          panel: targetPanel
        }
      }));
    }
  }
  
  /**
   * Initialize accordions functionality
   */
  function initAccordions() {
    const accordions = document.querySelectorAll('.accordion');
    
    accordions.forEach(function(accordion) {
      const headers = accordion.querySelectorAll('.accordion-header');
      
      headers.forEach(function(header, index) {
        const content = header.nextElementSibling;
        
        if (!content || !content.classList.contains('accordion-content')) {
          return;
        }
        
        const id = content.id || `accordion-content-${index}`;
        content.id = id;
        
        // Set ARIA attributes
        header.setAttribute('aria-expanded', header.classList.contains('is-active') ? 'true' : 'false');
        header.setAttribute('aria-controls', id);
        
        if (!header.classList.contains('is-active')) {
          content.hidden = true;
        }
        
        // Toggle accordion on click
        header.addEventListener('click', function() {
          const isExpanded = header.getAttribute('aria-expanded') === 'true';
          
          // Check if accordion allows multiple open panels
          if (!accordion.classList.contains('accordion-multiple')) {
            // Close all other accordions
            headers.forEach(function(otherHeader) {
              if (otherHeader !== header) {
                otherHeader.classList.remove('is-active');
                otherHeader.setAttribute('aria-expanded', 'false');
                
                const otherContent = otherHeader.nextElementSibling;
                if (otherContent && otherContent.classList.contains('accordion-content')) {
                  otherContent.hidden = true;
                }
              }
            });
          }
          
          // Toggle current accordion
          header.classList.toggle('is-active', !isExpanded);
          header.setAttribute('aria-expanded', !isExpanded);
          content.hidden = isExpanded;
          
          // Dispatch event
          accordion.dispatchEvent(new CustomEvent('accordionToggled', {
            detail: {
              header: header,
              content: content,
              expanded: !isExpanded
            }
          }));
        });
      });
    });
  }
  
  /**
   * Initialize tooltips functionality
   */
  function initTooltips() {
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(function(trigger) {
      const tooltipText = trigger.getAttribute('data-tooltip');
      const tooltipPosition = trigger.getAttribute('data-tooltip-position') || 'top';
      
      if (!tooltipText) {
        return;
      }
      
      // Create tooltip element
      const tooltip = document.createElement('div');
      tooltip.className = `tooltip tooltip-${tooltipPosition}`;
      tooltip.textContent = tooltipText;
      tooltip.setAttribute('role', 'tooltip');
      tooltip.hidden = true;
      
      // Add tooltip to DOM
      document.body.appendChild(tooltip);
      
      // Show tooltip on hover/focus
      function showTooltip() {
        const triggerRect = trigger.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        
        // Calculate position
        let top, left;
        
        switch (tooltipPosition) {
          case 'top':
            top = triggerRect.top - tooltipRect.height - 10;
            left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);
            break;
          case 'bottom':
            top = triggerRect.bottom + 10;
            left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);
            break;
          case 'left':
            top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2);
            left = triggerRect.left - tooltipRect.width - 10;
            break;
          case 'right':
            top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2);
            left = triggerRect.right + 10;
            break;
        }
        
        // Adjust position to keep tooltip in viewport
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        
        if (left < 10) left = 10;
        if (left + tooltipRect.width > viewportWidth - 10) left = viewportWidth - tooltipRect.width - 10;
        if (top < 10) top = 10;
        if (top + tooltipRect.height > viewportHeight - 10) top = viewportHeight - tooltipRect.height - 10;
        
        // Set position
        tooltip.style.top = `${top + window.pageYOffset}px`;
        tooltip.style.left = `${left + window.pageXOffset}px`;
        
        // Show tooltip
        tooltip.hidden = false;
      }
      
      // Hide tooltip
      function hideTooltip() {
        tooltip.hidden = true;
      }
      
      // Add event listeners
      trigger.addEventListener('mouseenter', showTooltip);
      trigger.addEventListener('mouseleave', hideTooltip);
      trigger.addEventListener('focus', showTooltip);
      trigger.addEventListener('blur', hideTooltip);
      
      // Clean up on trigger removal
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type === 'childList' && Array.from(mutation.removedNodes).includes(trigger)) {
            tooltip.remove();
            observer.disconnect();
          }
        });
      });
      
      observer.observe(trigger.parentNode, { childList: true });
    });
  }
  
  /**
   * Initialize form validation
   */
  function initFormValidation() {
    const forms = document.querySelectorAll('form.validate');
    
    forms.forEach(function(form) {
      // Add novalidate attribute to disable browser's default validation
      form.setAttribute('novalidate', '');
      
      // Validate form on submit
      form.addEventListener('submit', function(event) {
        if (!validateForm(form)) {
          event.preventDefault();
        }
      });
      
      // Validate fields on blur
      const fields = form.querySelectorAll('input, select, textarea');
      fields.forEach(function(field) {
        field.addEventListener('blur', function() {
          validateField(field);
        });
        
        // For select and radio/checkbox, validate on change
        if (field.tagName === 'SELECT' || field.type === 'radio' || field.type === 'checkbox') {
          field.addEventListener('change', function() {
            validateField(field);
          });
        }
      });
    });
    
    /**
     * Validate a form
     * @param {Element} form - The form to validate
     * @return {boolean} True if form is valid
     */
    function validateForm(form) {
      let isValid = true;
      const fields = form.querySelectorAll('input, select, textarea');
      
      fields.forEach(function(field) {
        if (!validateField(field)) {
          isValid = false;
        }
      });
      
      return isValid;
    }
    
    /**
     * Validate a field
     * @param {Element} field - The field to validate
     * @return {boolean} True if field is valid
     */
    function validateField(field) {
      // Skip disabled or hidden fields
      if (field.disabled || field.type === 'hidden') {
        return true;
      }
      
      const errorContainer = field.parentNode.querySelector('.error-message');
      let isValid = true;
      let errorMessage = '';
      
      // Required validation
      if (field.hasAttribute('required') && !field.value.trim()) {
        isValid = false;
        errorMessage = field.getAttribute('data-required-message') || 'This field is required.';
      }
      
      // Email validation
      if (field.type === 'email' && field.value.trim() && !validateEmail(field.value)) {
        isValid = false;
        errorMessage = field.getAttribute('data-email-message') || 'Please enter a valid email address.';
      }
      
      // URL validation
      if (field.type === 'url' && field.value.trim() && !validateUrl(field.value)) {
        isValid = false;
        errorMessage = field.getAttribute('data-url-message') || 'Please enter a valid URL.';
      }
      
      // Pattern validation
      if (field.hasAttribute('pattern') && field.value.trim()) {
        const pattern = new RegExp(field.getAttribute('pattern'));
        if (!pattern.test(field.value)) {
          isValid = false;
          errorMessage = field.getAttribute('data-pattern-message') || 'Please match the requested format.';
        }
      }
      
      // Min/max validation for number inputs
      if (field.type === 'number' && field.value.trim()) {
        const value = parseFloat(field.value);
        
        if (field.hasAttribute('min') && value < parseFloat(field.getAttribute('min'))) {
          isValid = false;
          errorMessage = field.getAttribute('data-min-message') || `Value must be greater than or equal to ${field.getAttribute('min')}.`;
        }
        
        if (field.hasAttribute('max') && value > parseFloat(field.getAttribute('max'))) {
          isValid = false;
          errorMessage = field.getAttribute('data-max-message') || `Value must be less than or equal to ${field.getAttribute('max')}.`;
        }
      }
      
      // Min/max length validation
      if (field.value.trim()) {
        if (field.hasAttribute('minlength') && field.value.length < parseInt(field.getAttribute('minlength'))) {
          isValid = false;
          errorMessage = field.getAttribute('data-minlength-message') || `Please enter at least ${field.getAttribute('minlength')} characters.`;
        }
        
        if (field.hasAttribute('maxlength') && field.value.length > parseInt(field.getAttribute('maxlength'))) {
          isValid = false;
          errorMessage = field.getAttribute('data-maxlength-message') || `Please enter no more than ${field.getAttribute('maxlength')} characters.`;
        }
      }
      
      // Update field state
      if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        field.setAttribute('aria-invalid', 'false');
        
        // Remove error message if it exists
        if (errorContainer) {
          errorContainer.textContent = '';
          errorContainer.hidden = true;
        }
      } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');
        
        // Add or update error message
        if (errorContainer) {
          errorContainer.textContent = errorMessage;
          errorContainer.hidden = false;
        } else {
          const newErrorContainer = document.createElement('div');
          newErrorContainer.className = 'error-message';
          newErrorContainer.textContent = errorMessage;
          newErrorContainer.setAttribute('aria-live', 'polite');
          field.parentNode.appendChild(newErrorContainer);
        }
      }
      
      return isValid;
    }
    
    /**
     * Validate email address
     * @param {string} email - The email address to validate
     * @return {boolean} True if email is valid
     */
    function validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }
    
    /**
     * Validate URL
     * @param {string} url - The URL to validate
     * @return {boolean} True if URL is valid
     */
    function validateUrl(url) {
      try {
        new URL(url);
        return true;
      } catch (_) {
        return false;
      }
    }
  }
  
  /**
   * Initialize animations
   */
  function initAnimations() {
    if (!('IntersectionObserver' in window)) {
      return;
    }
    
    const animatedElements = document.querySelectorAll('.animate');
    
    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          observer.unobserve(entry.target);
        }
      });
    }, {
      root: null,
      rootMargin: '0px',
      threshold: 0.1
    });
    
    animatedElements.forEach(function(element) {
      observer.observe(element);
    });
  }
  
  /**
   * Initialize back to top button
   */
  function initBackToTop() {
    const backToTopButton = document.querySelector('.back-to-top');
    
    if (!backToTopButton) {
      return;
    }
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.add('is-visible');
      } else {
        backToTopButton.classList.remove('is-visible');
      }
    });
    
    // Scroll to top when clicked
    backToTopButton.addEventListener('click', function(event) {
      event.preventDefault();
      
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }
  
  /**
   * Initialize search toggle
   */
  function initSearchToggle() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchForm = document.querySelector('.search-form');
    
    if (!searchToggle || !searchForm) {
      return;
    }
    
    // Hide search form initially
    searchForm.hidden = true;
    
    // Toggle search form
    searchToggle.addEventListener('click', function(event) {
      event.preventDefault();
      
      const isExpanded = searchToggle.getAttribute('aria-expanded') === 'true';
      
      searchToggle.setAttribute('aria-expanded', !isExpanded);
      searchForm.hidden = isExpanded;
      
      if (!isExpanded) {
        // Focus search input
        const searchInput = searchForm.querySelector('input[type="search"]');
        if (searchInput) {
          setTimeout(function() {
            searchInput.focus();
          }, 100);
        }
      }
    });
    
    // Close search form when clicking outside
    document.addEventListener('click', function(event) {
      if (!searchForm.hidden && 
          !searchForm.contains(event.target) && 
          !searchToggle.contains(event.target)) {
        searchToggle.setAttribute('aria-expanded', 'false');
        searchForm.hidden = true;
      }
    });
    
    // Close search form when pressing Escape
    document.addEventListener('keyup', function(event) {
      if (event.key === 'Escape' && !searchForm.hidden) {
        searchToggle.setAttribute('aria-expanded', 'false');
        searchForm.hidden = true;
        searchToggle.focus();
      }
    });
  }
  
  /**
   * Initialize quantity controls for WooCommerce
   */
  function initQuantityControls() {
    const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
    
    quantityInputs.forEach(function(input) {
      const wrapper = input.parentNode;
      
      // Create minus button if it doesn't exist
      if (!wrapper.querySelector('.quantity-minus')) {
        const minusButton = document.createElement('button');
        minusButton.type = 'button';
        minusButton.className = 'quantity-minus';
        minusButton.textContent = '-';
        minusButton.setAttribute('aria-label', 'Decrease quantity');
        wrapper.insertBefore(minusButton, input);
        
        // Decrease quantity when clicked
        minusButton.addEventListener('click', function() {
          const currentValue = parseInt(input.value, 10);
          const min = input.hasAttribute('min') ? parseInt(input.getAttribute('min'), 10) : 1;
          
          if (currentValue > min) {
            input.value = currentValue - 1;
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });
      }
      
      // Create plus button if it doesn't exist
      if (!wrapper.querySelector('.quantity-plus')) {
        const plusButton = document.createElement('button');
        plusButton.type = 'button';
        plusButton.className = 'quantity-plus';
        plusButton.textContent = '+';
        plusButton.setAttribute('aria-label', 'Increase quantity');
        wrapper.appendChild(plusButton);
        
        // Increase quantity when clicked
        plusButton.addEventListener('click', function() {
          const currentValue = parseInt(input.value, 10);
          const max = input.hasAttribute('max') ? parseInt(input.getAttribute('max'), 10) : null;
          
          if (max === null || currentValue < max) {
            input.value = currentValue + 1;
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });
      }
    });
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();