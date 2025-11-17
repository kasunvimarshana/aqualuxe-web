/**
 * AquaLuxe WordPress Theme
 * Modal Component
 */

/**
 * Modal component for the AquaLuxe theme.
 */
(function() {
  /**
   * Modal class
   */
  class Modal {
    /**
     * Constructor
     * @param {HTMLElement|string} element - Modal element or selector
     * @param {Object} options - Modal options
     */
    constructor(element, options = {}) {
      // Get element
      this.modal = typeof element === 'string' ? document.querySelector(element) : element;
      
      if (!this.modal) {
        console.error('Modal element not found');
        return;
      }
      
      // Default options
      const defaultOptions = {
        closeOnEscape: true,
        closeOnOverlayClick: true,
        closeOnButtonClick: true,
        preventScroll: true,
        animationSpeed: 300,
        onOpen: null,
        onClose: null,
        openClass: 'is-active',
        openSelector: null,
        closeSelector: '.modal-close, .modal-cancel',
        overlaySelector: '.modal-overlay',
        contentSelector: '.modal-content',
        focusableElementsSelector: 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
      };
      
      // Merge options
      this.options = { ...defaultOptions, ...options };
      
      // Get elements
      this.overlay = this.modal.querySelector(this.options.overlaySelector);
      this.content = this.modal.querySelector(this.options.contentSelector);
      this.closeButtons = this.modal.querySelectorAll(this.options.closeSelector);
      
      // Set state
      this.isOpen = false;
      this.previousActiveElement = null;
      this.focusableElements = [];
      
      // Initialize
      this.init();
    }
    
    /**
     * Initialize modal
     */
    init() {
      // Set up ARIA attributes
      this.setupAria();
      
      // Set up events
      this.setupEvents();
      
      // Add initialized class
      this.modal.classList.add('modal-initialized');
      
      // Set up open triggers if selector provided
      if (this.options.openSelector) {
        this.setupOpenTriggers();
      }
    }
    
    /**
     * Set up ARIA attributes
     */
    setupAria() {
      const id = this.modal.id || `modal-${this.getRandomId()}`;
      const titleId = `${id}-title`;
      const descriptionId = `${id}-description`;
      
      // Set modal attributes
      this.modal.setAttribute('id', id);
      this.modal.setAttribute('role', 'dialog');
      this.modal.setAttribute('aria-modal', 'true');
      this.modal.setAttribute('aria-hidden', 'true');
      
      // Find title and description
      const title = this.modal.querySelector('.modal-title');
      const description = this.modal.querySelector('.modal-description');
      
      // Set title attributes
      if (title) {
        title.setAttribute('id', titleId);
        this.modal.setAttribute('aria-labelledby', titleId);
      }
      
      // Set description attributes
      if (description) {
        description.setAttribute('id', descriptionId);
        this.modal.setAttribute('aria-describedby', descriptionId);
      }
      
      // Set close button attributes
      this.closeButtons.forEach(button => {
        if (!button.getAttribute('aria-label')) {
          button.setAttribute('aria-label', 'Close modal');
        }
      });
    }
    
    /**
     * Set up events
     */
    setupEvents() {
      // Close button click
      if (this.options.closeOnButtonClick) {
        this.closeButtons.forEach(button => {
          button.addEventListener('click', (e) => {
            e.preventDefault();
            this.close();
          });
        });
      }
      
      // Overlay click
      if (this.options.closeOnOverlayClick && this.overlay) {
        this.overlay.addEventListener('click', (e) => {
          if (e.target === this.overlay) {
            e.preventDefault();
            this.close();
          }
        });
      }
      
      // Escape key
      if (this.options.closeOnEscape) {
        document.addEventListener('keydown', (e) => {
          if (this.isOpen && (e.key === 'Escape' || e.keyCode === 27)) {
            e.preventDefault();
            this.close();
          }
        });
      }
      
      // Trap focus
      this.modal.addEventListener('keydown', (e) => {
        if (this.isOpen && (e.key === 'Tab' || e.keyCode === 9)) {
          this.trapFocus(e);
        }
      });
    }
    
    /**
     * Set up open triggers
     */
    setupOpenTriggers() {
      const triggers = document.querySelectorAll(this.options.openSelector);
      
      triggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
          e.preventDefault();
          this.open();
        });
      });
    }
    
    /**
     * Open modal
     */
    open() {
      if (this.isOpen) {
        return;
      }
      
      // Store active element
      this.previousActiveElement = document.activeElement;
      
      // Update state
      this.isOpen = true;
      
      // Update attributes
      this.modal.setAttribute('aria-hidden', 'false');
      
      // Add open class
      this.modal.classList.add(this.options.openClass);
      
      // Prevent body scroll
      if (this.options.preventScroll) {
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = this.getScrollbarWidth() + 'px';
      }
      
      // Get focusable elements
      this.focusableElements = Array.from(this.modal.querySelectorAll(this.options.focusableElementsSelector));
      
      // Focus first element
      setTimeout(() => {
        if (this.focusableElements.length) {
          this.focusableElements[0].focus();
        } else {
          this.modal.focus();
        }
      }, this.options.animationSpeed);
      
      // Call onOpen callback
      if (typeof this.options.onOpen === 'function') {
        this.options.onOpen(this.modal);
      }
      
      // Trigger event
      this.modal.dispatchEvent(new CustomEvent('modalOpen', {
        bubbles: true
      }));
    }
    
    /**
     * Close modal
     */
    close() {
      if (!this.isOpen) {
        return;
      }
      
      // Update state
      this.isOpen = false;
      
      // Update attributes
      this.modal.setAttribute('aria-hidden', 'true');
      
      // Remove open class
      this.modal.classList.remove(this.options.openClass);
      
      // Restore body scroll
      if (this.options.preventScroll) {
        setTimeout(() => {
          document.body.style.overflow = '';
          document.body.style.paddingRight = '';
        }, this.options.animationSpeed);
      }
      
      // Restore focus
      if (this.previousActiveElement) {
        setTimeout(() => {
          this.previousActiveElement.focus();
        }, this.options.animationSpeed);
      }
      
      // Call onClose callback
      if (typeof this.options.onClose === 'function') {
        this.options.onClose(this.modal);
      }
      
      // Trigger event
      this.modal.dispatchEvent(new CustomEvent('modalClose', {
        bubbles: true
      }));
    }
    
    /**
     * Trap focus within modal
     * @param {Event} e - Keydown event
     */
    trapFocus(e) {
      // If no focusable elements, do nothing
      if (!this.focusableElements.length) {
        e.preventDefault();
        return;
      }
      
      const firstElement = this.focusableElements[0];
      const lastElement = this.focusableElements[this.focusableElements.length - 1];
      const isTabPressed = (e.key === 'Tab' || e.keyCode === 9);
      
      if (!isTabPressed) {
        return;
      }
      
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
    
    /**
     * Get scrollbar width
     * @return {number} - Scrollbar width
     */
    getScrollbarWidth() {
      // Create a temporary div
      const div = document.createElement('div');
      
      // Apply styles to make it scrollable
      div.style.visibility = 'hidden';
      div.style.overflow = 'scroll';
      div.style.msOverflowStyle = 'scrollbar';
      document.body.appendChild(div);
      
      // Create inner div
      const inner = document.createElement('div');
      div.appendChild(inner);
      
      // Calculate width difference
      const width = div.offsetWidth - inner.offsetWidth;
      
      // Remove divs
      div.parentNode.removeChild(div);
      
      return width;
    }
    
    /**
     * Get random ID
     * @return {string} - Random ID
     */
    getRandomId() {
      return Math.random().toString(36).substring(2, 9);
    }
    
    /**
     * Destroy modal
     */
    destroy() {
      // Close modal if open
      if (this.isOpen) {
        this.close();
      }
      
      // Remove event listeners
      this.closeButtons.forEach(button => {
        button.removeEventListener('click', this.close);
      });
      
      if (this.overlay) {
        this.overlay.removeEventListener('click', this.close);
      }
      
      // Remove ARIA attributes
      this.modal.removeAttribute('role');
      this.modal.removeAttribute('aria-modal');
      this.modal.removeAttribute('aria-hidden');
      this.modal.removeAttribute('aria-labelledby');
      this.modal.removeAttribute('aria-describedby');
      
      // Remove initialized class
      this.modal.classList.remove('modal-initialized');
    }
  }
  
  // Add Modal to AquaLuxe object
  window.AquaLuxe = window.AquaLuxe || {};
  window.AquaLuxe.Modal = Modal;
  
  // Initialize modals
  document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
      // Get options from data attributes
      const options = {};
      
      if (modal.dataset.closeOnEscape === 'false') {
        options.closeOnEscape = false;
      }
      
      if (modal.dataset.closeOnOverlayClick === 'false') {
        options.closeOnOverlayClick = false;
      }
      
      if (modal.dataset.closeOnButtonClick === 'false') {
        options.closeOnButtonClick = false;
      }
      
      if (modal.dataset.preventScroll === 'false') {
        options.preventScroll = false;
      }
      
      if (modal.dataset.animationSpeed) {
        options.animationSpeed = parseInt(modal.dataset.animationSpeed);
      }
      
      if (modal.dataset.openSelector) {
        options.openSelector = modal.dataset.openSelector;
      }
      
      if (modal.dataset.closeSelector) {
        options.closeSelector = modal.dataset.closeSelector;
      }
      
      // Initialize modal
      new Modal(modal, options);
    });
  });
})();