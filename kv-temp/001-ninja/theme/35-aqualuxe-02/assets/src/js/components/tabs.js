/**
 * AquaLuxe WordPress Theme
 * Tabs Component
 */

/**
 * Tabs component for the AquaLuxe theme.
 */
(function() {
  /**
   * Tabs class
   */
  class Tabs {
    /**
     * Constructor
     * @param {HTMLElement|string} element - Tabs container element or selector
     * @param {Object} options - Tabs options
     */
    constructor(element, options = {}) {
      // Get element
      this.container = typeof element === 'string' ? document.querySelector(element) : element;
      
      if (!this.container) {
        console.error('Tabs container element not found');
        return;
      }
      
      // Default options
      const defaultOptions = {
        defaultTab: 0,
        onChange: null,
        responsive: true,
        breakpoint: 768,
        accordion: true
      };
      
      // Merge options
      this.options = { ...defaultOptions, ...options };
      
      // Get elements
      this.tabList = this.container.querySelector('.tabs-list');
      this.tabs = Array.from(this.container.querySelectorAll('.tab-button'));
      this.panels = Array.from(this.container.querySelectorAll('.tab-panel'));
      
      if (!this.tabList || !this.tabs.length || !this.panels.length) {
        console.error('Tabs elements not found');
        return;
      }
      
      // Set state
      this.currentTab = this.options.defaultTab;
      this.isAccordion = false;
      
      // Initialize
      this.init();
    }
    
    /**
     * Initialize tabs
     */
    init() {
      // Set up ARIA attributes
      this.setupAria();
      
      // Set up events
      this.setupEvents();
      
      // Check responsive mode
      if (this.options.responsive) {
        this.checkResponsiveMode();
        
        // Add resize listener
        window.addEventListener('resize', AquaLuxe.utils.debounce(() => {
          this.checkResponsiveMode();
        }, 250));
      }
      
      // Show default tab
      this.showTab(this.currentTab);
      
      // Add initialized class
      this.container.classList.add('tabs-initialized');
    }
    
    /**
     * Set up ARIA attributes
     */
    setupAria() {
      // Set tab list role
      this.tabList.setAttribute('role', 'tablist');
      
      // Set tab attributes
      this.tabs.forEach((tab, index) => {
        const id = tab.id || `tab-${this.getRandomId()}-${index}`;
        const panelId = this.panels[index].id || `panel-${this.getRandomId()}-${index}`;
        
        // Set tab attributes
        tab.setAttribute('role', 'tab');
        tab.setAttribute('id', id);
        tab.setAttribute('aria-controls', panelId);
        tab.setAttribute('aria-selected', 'false');
        tab.setAttribute('tabindex', '-1');
        
        // Set panel attributes
        this.panels[index].setAttribute('role', 'tabpanel');
        this.panels[index].setAttribute('id', panelId);
        this.panels[index].setAttribute('aria-labelledby', id);
        this.panels[index].setAttribute('hidden', '');
      });
    }
    
    /**
     * Set up events
     */
    setupEvents() {
      // Tab click events
      this.tabs.forEach((tab, index) => {
        tab.addEventListener('click', (e) => {
          e.preventDefault();
          this.showTab(index);
        });
        
        // Keyboard navigation
        tab.addEventListener('keydown', (e) => {
          this.handleKeydown(e, index);
        });
      });
    }
    
    /**
     * Handle keydown events
     * @param {Event} e - Keydown event
     * @param {number} index - Tab index
     */
    handleKeydown(e, index) {
      // Only handle events in tabs mode
      if (this.isAccordion) return;
      
      const tabCount = this.tabs.length;
      let newIndex = index;
      
      switch (e.key) {
        case 'ArrowLeft':
        case 'ArrowUp':
          newIndex = (index - 1 + tabCount) % tabCount;
          e.preventDefault();
          break;
        case 'ArrowRight':
        case 'ArrowDown':
          newIndex = (index + 1) % tabCount;
          e.preventDefault();
          break;
        case 'Home':
          newIndex = 0;
          e.preventDefault();
          break;
        case 'End':
          newIndex = tabCount - 1;
          e.preventDefault();
          break;
        default:
          return;
      }
      
      this.showTab(newIndex);
      this.tabs[newIndex].focus();
    }
    
    /**
     * Show tab
     * @param {number} index - Tab index
     */
    showTab(index) {
      // Validate index
      if (index < 0 || index >= this.tabs.length) {
        return;
      }
      
      // Update current tab
      this.currentTab = index;
      
      if (this.isAccordion) {
        // Accordion mode
        this.tabs.forEach((tab, i) => {
          const isActive = i === index;
          const panel = this.panels[i];
          
          // Update tab
          tab.setAttribute('aria-expanded', isActive ? 'true' : 'false');
          tab.classList.toggle('active', isActive);
          
          // Update panel
          if (isActive) {
            panel.removeAttribute('hidden');
            panel.classList.add('active');
          } else {
            panel.setAttribute('hidden', '');
            panel.classList.remove('active');
          }
        });
      } else {
        // Tabs mode
        this.tabs.forEach((tab, i) => {
          const isActive = i === index;
          const panel = this.panels[i];
          
          // Update tab
          tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
          tab.setAttribute('tabindex', isActive ? '0' : '-1');
          tab.classList.toggle('active', isActive);
          
          // Update panel
          if (isActive) {
            panel.removeAttribute('hidden');
            panel.classList.add('active');
          } else {
            panel.setAttribute('hidden', '');
            panel.classList.remove('active');
          }
        });
      }
      
      // Call onChange callback
      if (typeof this.options.onChange === 'function') {
        this.options.onChange(index, this.tabs[index], this.panels[index]);
      }
      
      // Trigger change event
      this.container.dispatchEvent(new CustomEvent('tabChange', {
        detail: {
          index: index,
          tab: this.tabs[index],
          panel: this.panels[index]
        }
      }));
    }
    
    /**
     * Check responsive mode
     */
    checkResponsiveMode() {
      const isMobile = window.innerWidth < this.options.breakpoint;
      
      if (isMobile && this.options.accordion && !this.isAccordion) {
        this.switchToAccordion();
      } else if (!isMobile && this.isAccordion) {
        this.switchToTabs();
      }
    }
    
    /**
     * Switch to accordion mode
     */
    switchToAccordion() {
      this.isAccordion = true;
      
      // Update container class
      this.container.classList.add('tabs-accordion-mode');
      this.container.classList.remove('tabs-tabs-mode');
      
      // Update ARIA attributes
      this.tabList.removeAttribute('role');
      
      this.tabs.forEach((tab, index) => {
        tab.removeAttribute('role');
        tab.removeAttribute('aria-selected');
        tab.setAttribute('aria-expanded', index === this.currentTab ? 'true' : 'false');
      });
      
      // Show current tab
      this.showTab(this.currentTab);
    }
    
    /**
     * Switch to tabs mode
     */
    switchToTabs() {
      this.isAccordion = false;
      
      // Update container class
      this.container.classList.add('tabs-tabs-mode');
      this.container.classList.remove('tabs-accordion-mode');
      
      // Update ARIA attributes
      this.tabList.setAttribute('role', 'tablist');
      
      this.tabs.forEach((tab, index) => {
        tab.setAttribute('role', 'tab');
        tab.setAttribute('aria-selected', index === this.currentTab ? 'true' : 'false');
        tab.removeAttribute('aria-expanded');
      });
      
      // Show current tab
      this.showTab(this.currentTab);
    }
    
    /**
     * Get random ID
     * @return {string} - Random ID
     */
    getRandomId() {
      return Math.random().toString(36).substring(2, 9);
    }
    
    /**
     * Destroy tabs
     */
    destroy() {
      // Remove event listeners
      this.tabs.forEach(tab => {
        tab.removeEventListener('click', this.handleClick);
        tab.removeEventListener('keydown', this.handleKeydown);
      });
      
      // Reset ARIA attributes
      this.tabList.removeAttribute('role');
      
      this.tabs.forEach(tab => {
        tab.removeAttribute('role');
        tab.removeAttribute('aria-selected');
        tab.removeAttribute('aria-controls');
        tab.removeAttribute('tabindex');
        tab.classList.remove('active');
      });
      
      this.panels.forEach(panel => {
        panel.removeAttribute('role');
        panel.removeAttribute('aria-labelledby');
        panel.removeAttribute('hidden');
        panel.classList.remove('active');
      });
      
      // Remove classes
      this.container.classList.remove('tabs-initialized', 'tabs-tabs-mode', 'tabs-accordion-mode');
    }
  }
  
  // Add Tabs to AquaLuxe object
  window.AquaLuxe = window.AquaLuxe || {};
  window.AquaLuxe.Tabs = Tabs;
  
  // Initialize tabs
  document.addEventListener('DOMContentLoaded', () => {
    const tabsContainers = document.querySelectorAll('.tabs-container');
    
    tabsContainers.forEach(container => {
      // Get options from data attributes
      const options = {};
      
      if (container.dataset.defaultTab) {
        options.defaultTab = parseInt(container.dataset.defaultTab);
      }
      
      if (container.dataset.responsive === 'false') {
        options.responsive = false;
      }
      
      if (container.dataset.breakpoint) {
        options.breakpoint = parseInt(container.dataset.breakpoint);
      }
      
      if (container.dataset.accordion === 'false') {
        options.accordion = false;
      }
      
      // Initialize tabs
      new Tabs(container, options);
    });
  });
})();