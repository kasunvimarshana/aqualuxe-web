/**
 * AquaLuxe Accessibility Component
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe Accessibility Class
 */
class AquaLuxeAccessibility {
  /**
   * Constructor
   */
  constructor() {
    this.isInitialized = false;
    
    // Elements
    this.skipLink = null;
    this.focusableElements = null;
  }

  /**
   * Initialize accessibility features
   */
  init() {
    // Skip if already initialized
    if (this.isInitialized) {
      return;
    }
    
    // Get elements
    this.skipLink = document.querySelector('.skip-link');
    
    // Bind events
    this.bindEvents();
    
    // Add focus outlines for keyboard navigation
    this.setupFocusOutlines();
    
    // Set initialized flag
    this.isInitialized = true;
  }

  /**
   * Bind events
   */
  bindEvents() {
    // Skip link click
    if (this.skipLink) {
      this.skipLink.addEventListener('click', (e) => this.handleSkipLinkClick(e));
    }
    
    // Tab key detection for focus outlines
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        document.body.classList.add('is-tabbing');
      }
    });
    
    // Mouse detection to hide focus outlines
    document.addEventListener('mousedown', () => {
      document.body.classList.remove('is-tabbing');
    });
  }

  /**
   * Handle skip link click
   *
   * @param {Event} e Click event
   */
  handleSkipLinkClick(e) {
    // Get target
    const targetId = this.skipLink.getAttribute('href');
    const target = document.querySelector(targetId);
    
    // Skip if no target
    if (!target) {
      return;
    }
    
    // Set focus to target
    e.preventDefault();
    target.setAttribute('tabindex', '-1');
    target.focus();
    
    // Remove tabindex after blur
    target.addEventListener('blur', () => {
      target.removeAttribute('tabindex');
    }, { once: true });
  }

  /**
   * Setup focus outlines for keyboard navigation
   */
  setupFocusOutlines() {
    // Add class to body for CSS targeting
    document.body.classList.add('has-accessibility-support');
    
    // Get all focusable elements
    this.focusableElements = document.querySelectorAll(
      'a, button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
    );
    
    // Add focus event listeners
    this.focusableElements.forEach((element) => {
      // Add focus class
      element.addEventListener('focus', () => {
        element.classList.add('is-focused');
      });
      
      // Remove focus class
      element.addEventListener('blur', () => {
        element.classList.remove('is-focused');
      });
    });
  }

  /**
   * Make element accessible
   *
   * @param {HTMLElement} element Element to make accessible
   * @param {Object} options Accessibility options
   */
  makeAccessible(element, options = {}) {
    // Skip if no element
    if (!element) {
      return;
    }
    
    // Default options
    const defaults = {
      role: '',
      tabindex: '',
      ariaLabel: '',
      ariaLabelledby: '',
      ariaDescribedby: '',
      ariaHidden: '',
      ariaExpanded: '',
      ariaControls: '',
    };
    
    // Merge options
    const settings = { ...defaults, ...options };
    
    // Set attributes
    if (settings.role) {
      element.setAttribute('role', settings.role);
    }
    
    if (settings.tabindex) {
      element.setAttribute('tabindex', settings.tabindex);
    }
    
    if (settings.ariaLabel) {
      element.setAttribute('aria-label', settings.ariaLabel);
    }
    
    if (settings.ariaLabelledby) {
      element.setAttribute('aria-labelledby', settings.ariaLabelledby);
    }
    
    if (settings.ariaDescribedby) {
      element.setAttribute('aria-describedby', settings.ariaDescribedby);
    }
    
    if (settings.ariaHidden) {
      element.setAttribute('aria-hidden', settings.ariaHidden);
    }
    
    if (settings.ariaExpanded) {
      element.setAttribute('aria-expanded', settings.ariaExpanded);
    }
    
    if (settings.ariaControls) {
      element.setAttribute('aria-controls', settings.ariaControls);
    }
  }

  /**
   * Add screen reader text
   *
   * @param {HTMLElement} element Element to add screen reader text to
   * @param {string} text Screen reader text
   * @returns {HTMLElement} Screen reader text element
   */
  addScreenReaderText(element, text) {
    // Skip if no element or text
    if (!element || !text) {
      return null;
    }
    
    // Create screen reader text element
    const srText = document.createElement('span');
    srText.className = 'screen-reader-text';
    srText.textContent = text;
    
    // Add to element
    element.appendChild(srText);
    
    return srText;
  }
}

// Export for use in theme.js
export default AquaLuxeAccessibility;