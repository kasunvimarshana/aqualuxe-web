/**
 * AquaLuxe WordPress Theme
 * Accordion Component
 */

/**
 * Accordion component for the AquaLuxe theme.
 */
(function() {
  /**
   * Accordion class
   */
  class Accordion {
    /**
     * Constructor
     * @param {HTMLElement|string} element - Accordion element or selector
     * @param {Object} options - Accordion options
     */
    constructor(element, options = {}) {
      // Get element
      this.accordion = typeof element === 'string' ? document.querySelector(element) : element;
      
      if (!this.accordion) {
        console.error('Accordion element not found');
        return;
      }
      
      // Default options
      const defaultOptions = {
        multiExpand: false,
        defaultExpanded: null,
        expandOnLoad: false,
        collapseOthers: true,
        animationSpeed: 300,
        onToggle: null
      };
      
      // Merge options
      this.options = { ...defaultOptions, ...options };
      
      // Get elements
      this.items = Array.from(this.accordion.querySelectorAll('.accordion-item'));
      
      if (!this.items.length) {
        console.error('Accordion items not found');
        return;
      }
      
      // Initialize
      this.init();
    }
    
    /**
     * Initialize accordion
     */
    init() {
      // Set up ARIA attributes
      this.setupAria();
      
      // Set up events
      this.setupEvents();
      
      // Expand default items
      this.expandDefaultItems();
      
      // Add initialized class
      this.accordion.classList.add('accordion-initialized');
    }
    
    /**
     * Set up ARIA attributes
     */
    setupAria() {
      this.items.forEach((item, index) => {
        const header = item.querySelector('.accordion-header');
        const button = item.querySelector('.accordion-button');
        const content = item.querySelector('.accordion-content');
        
        if (!header || !button || !content) {
          return;
        }
        
        const id = content.id || `accordion-content-${this.getRandomId()}-${index}`;
        const headerId = header.id || `accordion-header-${this.getRandomId()}-${index}`;
        
        // Set header attributes
        header.setAttribute('id', headerId);
        
        // Set button attributes
        button.setAttribute('aria-expanded', 'false');
        button.setAttribute('aria-controls', id);
        button.setAttribute('id', `${headerId}-button`);
        
        // Set content attributes
        content.setAttribute('id', id);
        content.setAttribute('role', 'region');
        content.setAttribute('aria-labelledby', `${headerId}-button`);
        content.setAttribute('hidden', '');
        
        // Set initial height to 0
        content.style.height = '0';
        content.style.overflow = 'hidden';
        content.style.transition = `height ${this.options.animationSpeed}ms ease`;
      });
    }
    
    /**
     * Set up events
     */
    setupEvents() {
      this.items.forEach(item => {
        const button = item.querySelector('.accordion-button');
        
        if (button) {
          button.addEventListener('click', () => {
            this.toggleItem(item);
          });
          
          // Keyboard navigation
          button.addEventListener('keydown', (e) => {
            this.handleKeydown(e, item);
          });
        }
      });
    }
    
    /**
     * Handle keydown events
     * @param {Event} e - Keydown event
     * @param {HTMLElement} item - Accordion item
     */
    handleKeydown(e, item) {
      const index = this.items.indexOf(item);
      const button = item.querySelector('.accordion-button');
      
      switch (e.key) {
        case 'ArrowUp':
          e.preventDefault();
          this.focusPreviousItem(index);
          break;
        case 'ArrowDown':
          e.preventDefault();
          this.focusNextItem(index);
          break;
        case 'Home':
          e.preventDefault();
          this.focusFirstItem();
          break;
        case 'End':
          e.preventDefault();
          this.focusLastItem();
          break;
        case 'Enter':
        case ' ':
          e.preventDefault();
          this.toggleItem(item);
          break;
        default:
          break;
      }
    }
    
    /**
     * Focus previous item
     * @param {number} currentIndex - Current item index
     */
    focusPreviousItem(currentIndex) {
      const prevIndex = currentIndex - 1;
      
      if (prevIndex >= 0) {
        const prevItem = this.items[prevIndex];
        const prevButton = prevItem.querySelector('.accordion-button');
        
        if (prevButton) {
          prevButton.focus();
        }
      } else {
        this.focusLastItem();
      }
    }
    
    /**
     * Focus next item
     * @param {number} currentIndex - Current item index
     */
    focusNextItem(currentIndex) {
      const nextIndex = currentIndex + 1;
      
      if (nextIndex < this.items.length) {
        const nextItem = this.items[nextIndex];
        const nextButton = nextItem.querySelector('.accordion-button');
        
        if (nextButton) {
          nextButton.focus();
        }
      } else {
        this.focusFirstItem();
      }
    }
    
    /**
     * Focus first item
     */
    focusFirstItem() {
      const firstItem = this.items[0];
      const firstButton = firstItem.querySelector('.accordion-button');
      
      if (firstButton) {
        firstButton.focus();
      }
    }
    
    /**
     * Focus last item
     */
    focusLastItem() {
      const lastItem = this.items[this.items.length - 1];
      const lastButton = lastItem.querySelector('.accordion-button');
      
      if (lastButton) {
        lastButton.focus();
      }
    }
    
    /**
     * Expand default items
     */
    expandDefaultItems() {
      if (this.options.defaultExpanded !== null) {
        // Expand specific item
        if (typeof this.options.defaultExpanded === 'number') {
          const item = this.items[this.options.defaultExpanded];
          if (item) {
            this.expandItem(item);
          }
        } else if (Array.isArray(this.options.defaultExpanded)) {
          // Expand multiple items
          this.options.defaultExpanded.forEach(index => {
            const item = this.items[index];
            if (item) {
              this.expandItem(item);
            }
          });
        }
      } else if (this.options.expandOnLoad) {
        // Expand first item
        const firstItem = this.items[0];
        if (firstItem) {
          this.expandItem(firstItem);
        }
      }
    }
    
    /**
     * Toggle accordion item
     * @param {HTMLElement} item - Accordion item
     */
    toggleItem(item) {
      const isExpanded = item.classList.contains('expanded');
      
      if (isExpanded) {
        this.collapseItem(item);
      } else {
        // Collapse other items if needed
        if (this.options.collapseOthers && !this.options.multiExpand) {
          this.collapseAll(item);
        }
        
        this.expandItem(item);
      }
    }
    
    /**
     * Expand accordion item
     * @param {HTMLElement} item - Accordion item
     */
    expandItem(item) {
      const button = item.querySelector('.accordion-button');
      const content = item.querySelector('.accordion-content');
      
      if (!button || !content) {
        return;
      }
      
      // Update attributes
      button.setAttribute('aria-expanded', 'true');
      content.removeAttribute('hidden');
      
      // Add expanded class
      item.classList.add('expanded');
      
      // Animate height
      const contentHeight = content.scrollHeight;
      content.style.height = `${contentHeight}px`;
      
      // Remove fixed height after animation
      setTimeout(() => {
        content.style.height = 'auto';
      }, this.options.animationSpeed);
      
      // Call onToggle callback
      if (typeof this.options.onToggle === 'function') {
        this.options.onToggle(item, true);
      }
      
      // Trigger event
      this.accordion.dispatchEvent(new CustomEvent('accordionExpand', {
        detail: {
          item: item,
          index: this.items.indexOf(item)
        }
      }));
    }
    
    /**
     * Collapse accordion item
     * @param {HTMLElement} item - Accordion item
     */
    collapseItem(item) {
      const button = item.querySelector('.accordion-button');
      const content = item.querySelector('.accordion-content');
      
      if (!button || !content) {
        return;
      }
      
      // Set fixed height before animation
      const contentHeight = content.scrollHeight;
      content.style.height = `${contentHeight}px`;
      
      // Trigger reflow
      content.offsetHeight;
      
      // Update attributes
      button.setAttribute('aria-expanded', 'false');
      
      // Remove expanded class
      item.classList.remove('expanded');
      
      // Animate height
      content.style.height = '0';
      
      // Add hidden attribute after animation
      setTimeout(() => {
        content.setAttribute('hidden', '');
      }, this.options.animationSpeed);
      
      // Call onToggle callback
      if (typeof this.options.onToggle === 'function') {
        this.options.onToggle(item, false);
      }
      
      // Trigger event
      this.accordion.dispatchEvent(new CustomEvent('accordionCollapse', {
        detail: {
          item: item,
          index: this.items.indexOf(item)
        }
      }));
    }
    
    /**
     * Collapse all accordion items
     * @param {HTMLElement} [exceptItem] - Item to exclude
     */
    collapseAll(exceptItem = null) {
      this.items.forEach(item => {
        if (item !== exceptItem && item.classList.contains('expanded')) {
          this.collapseItem(item);
        }
      });
    }
    
    /**
     * Expand all accordion items
     */
    expandAll() {
      if (!this.options.multiExpand) {
        console.warn('Cannot expand all items when multiExpand is false');
        return;
      }
      
      this.items.forEach(item => {
        if (!item.classList.contains('expanded')) {
          this.expandItem(item);
        }
      });
    }
    
    /**
     * Get random ID
     * @return {string} - Random ID
     */
    getRandomId() {
      return Math.random().toString(36).substring(2, 9);
    }
    
    /**
     * Destroy accordion
     */
    destroy() {
      // Remove event listeners
      this.items.forEach(item => {
        const button = item.querySelector('.accordion-button');
        
        if (button) {
          button.removeEventListener('click', this.toggleItem);
          button.removeEventListener('keydown', this.handleKeydown);
        }
      });
      
      // Reset ARIA attributes
      this.items.forEach(item => {
        const button = item.querySelector('.accordion-button');
        const content = item.querySelector('.accordion-content');
        
        if (button) {
          button.removeAttribute('aria-expanded');
          button.removeAttribute('aria-controls');
        }
        
        if (content) {
          content.removeAttribute('role');
          content.removeAttribute('aria-labelledby');
          content.removeAttribute('hidden');
          content.style.height = '';
          content.style.overflow = '';
          content.style.transition = '';
        }
        
        item.classList.remove('expanded');
      });
      
      // Remove initialized class
      this.accordion.classList.remove('accordion-initialized');
    }
  }
  
  // Add Accordion to AquaLuxe object
  window.AquaLuxe = window.AquaLuxe || {};
  window.AquaLuxe.Accordion = Accordion;
  
  // Initialize accordions
  document.addEventListener('DOMContentLoaded', () => {
    const accordions = document.querySelectorAll('.accordion');
    
    accordions.forEach(accordion => {
      // Get options from data attributes
      const options = {};
      
      if (accordion.dataset.multiExpand === 'true') {
        options.multiExpand = true;
      }
      
      if (accordion.dataset.defaultExpanded) {
        try {
          options.defaultExpanded = JSON.parse(accordion.dataset.defaultExpanded);
        } catch (e) {
          console.error('Invalid defaultExpanded value:', e);
        }
      }
      
      if (accordion.dataset.expandOnLoad === 'true') {
        options.expandOnLoad = true;
      }
      
      if (accordion.dataset.collapseOthers === 'false') {
        options.collapseOthers = false;
      }
      
      if (accordion.dataset.animationSpeed) {
        options.animationSpeed = parseInt(accordion.dataset.animationSpeed);
      }
      
      // Initialize accordion
      new Accordion(accordion, options);
    });
  });
})();