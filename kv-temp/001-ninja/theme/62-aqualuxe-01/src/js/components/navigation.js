/**
 * AquaLuxe Navigation Component
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe Navigation Class
 */
class AquaLuxeNavigation {
  /**
   * Constructor
   */
  constructor() {
    this.mobileBreakpoint = 768;
    this.isInitialized = false;
    this.isMobile = window.innerWidth < this.mobileBreakpoint;
    
    // Elements
    this.header = null;
    this.menuToggle = null;
    this.mainMenu = null;
    this.subMenuToggles = null;
    this.searchToggle = null;
    this.searchForm = null;
  }

  /**
   * Initialize navigation
   */
  init() {
    // Skip if already initialized
    if (this.isInitialized) {
      return;
    }
    
    // Get elements
    this.header = document.querySelector('.site-header');
    this.menuToggle = document.querySelector('.menu-toggle');
    this.mainMenu = document.querySelector('.main-navigation');
    this.searchToggle = document.querySelector('.search-toggle');
    this.searchForm = document.querySelector('.search-form');
    
    // Skip if elements don't exist
    if (!this.header || !this.mainMenu) {
      return;
    }
    
    // Bind events
    this.bindEvents();
    
    // Add sub-menu toggles
    this.addSubMenuToggles();
    
    // Set initialized flag
    this.isInitialized = true;
    
    // Set initial state
    this.setInitialState();
  }

  /**
   * Bind events
   */
  bindEvents() {
    // Menu toggle click
    if (this.menuToggle) {
      this.menuToggle.addEventListener('click', (e) => this.toggleMenu(e));
    }
    
    // Search toggle click
    if (this.searchToggle && this.searchForm) {
      this.searchToggle.addEventListener('click', (e) => this.toggleSearch(e));
    }
    
    // Close menu on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        this.closeMenu();
        this.closeSearch();
      }
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (this.mainMenu && this.menuToggle) {
        if (!this.mainMenu.contains(e.target) && !this.menuToggle.contains(e.target)) {
          this.closeMenu();
        }
      }
      
      if (this.searchForm && this.searchToggle) {
        if (!this.searchForm.contains(e.target) && !this.searchToggle.contains(e.target)) {
          this.closeSearch();
        }
      }
    });
  }

  /**
   * Add sub-menu toggles
   */
  addSubMenuToggles() {
    // Get all menu items with children
    const menuItemsWithChildren = this.mainMenu.querySelectorAll('.menu-item-has-children, .page_item_has_children');
    
    // Add toggle button to each
    menuItemsWithChildren.forEach((item) => {
      const subMenu = item.querySelector('.sub-menu, .children');
      const link = item.querySelector('a');
      
      if (subMenu && link) {
        // Create toggle button
        const toggleButton = document.createElement('button');
        toggleButton.className = 'sub-menu-toggle';
        toggleButton.setAttribute('aria-expanded', 'false');
        toggleButton.innerHTML = '<span class="screen-reader-text">Toggle sub-menu</span><span class="icon" aria-hidden="true"></span>';
        
        // Insert after link
        link.parentNode.insertBefore(toggleButton, link.nextSibling);
        
        // Add click event
        toggleButton.addEventListener('click', (e) => this.toggleSubMenu(e, toggleButton, subMenu));
      }
    });
    
    // Store sub-menu toggles
    this.subMenuToggles = this.mainMenu.querySelectorAll('.sub-menu-toggle');
  }

  /**
   * Set initial state
   */
  setInitialState() {
    // Set mobile or desktop state
    if (this.isMobile) {
      this.setMobileState();
    } else {
      this.setDesktopState();
    }
  }

  /**
   * Set mobile state
   */
  setMobileState() {
    // Add mobile class to header
    if (this.header) {
      this.header.classList.add('is-mobile');
      this.header.classList.remove('is-desktop');
    }
    
    // Add mobile class to main menu
    if (this.mainMenu) {
      this.mainMenu.classList.add('is-mobile');
      this.mainMenu.classList.remove('is-desktop');
    }
    
    // Close menu
    this.closeMenu();
    
    // Close all sub-menus
    this.closeAllSubMenus();
  }

  /**
   * Set desktop state
   */
  setDesktopState() {
    // Add desktop class to header
    if (this.header) {
      this.header.classList.add('is-desktop');
      this.header.classList.remove('is-mobile');
    }
    
    // Add desktop class to main menu
    if (this.mainMenu) {
      this.mainMenu.classList.add('is-desktop');
      this.mainMenu.classList.remove('is-mobile');
      this.mainMenu.classList.remove('is-active');
    }
    
    // Reset menu toggle
    if (this.menuToggle) {
      this.menuToggle.classList.remove('is-active');
      this.menuToggle.setAttribute('aria-expanded', 'false');
    }
    
    // Close all sub-menus
    this.closeAllSubMenus();
  }

  /**
   * Toggle menu
   *
   * @param {Event} e Click event
   */
  toggleMenu(e) {
    e.preventDefault();
    
    // Toggle active class on menu
    if (this.mainMenu) {
      this.mainMenu.classList.toggle('is-active');
    }
    
    // Toggle active class on toggle button
    if (this.menuToggle) {
      this.menuToggle.classList.toggle('is-active');
      
      // Update aria-expanded
      const isExpanded = this.menuToggle.classList.contains('is-active');
      this.menuToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
    }
    
    // Close search if open
    this.closeSearch();
  }

  /**
   * Close menu
   */
  closeMenu() {
    // Remove active class from menu
    if (this.mainMenu) {
      this.mainMenu.classList.remove('is-active');
    }
    
    // Remove active class from toggle button
    if (this.menuToggle) {
      this.menuToggle.classList.remove('is-active');
      this.menuToggle.setAttribute('aria-expanded', 'false');
    }
    
    // Close all sub-menus
    this.closeAllSubMenus();
  }

  /**
   * Toggle sub-menu
   *
   * @param {Event} e Click event
   * @param {HTMLElement} toggle Toggle button
   * @param {HTMLElement} subMenu Sub-menu element
   */
  toggleSubMenu(e, toggle, subMenu) {
    e.preventDefault();
    
    // Toggle active class on sub-menu
    subMenu.classList.toggle('is-active');
    
    // Toggle active class on toggle button
    toggle.classList.toggle('is-active');
    
    // Update aria-expanded
    const isExpanded = toggle.classList.contains('is-active');
    toggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
  }

  /**
   * Close all sub-menus
   */
  closeAllSubMenus() {
    // Skip if no sub-menu toggles
    if (!this.subMenuToggles) {
      return;
    }
    
    // Close each sub-menu
    this.subMenuToggles.forEach((toggle) => {
      // Get sub-menu
      const subMenu = toggle.nextElementSibling;
      
      // Remove active class from sub-menu
      if (subMenu) {
        subMenu.classList.remove('is-active');
      }
      
      // Remove active class from toggle button
      toggle.classList.remove('is-active');
      toggle.setAttribute('aria-expanded', 'false');
    });
  }

  /**
   * Toggle search
   *
   * @param {Event} e Click event
   */
  toggleSearch(e) {
    e.preventDefault();
    
    // Skip if no search form
    if (!this.searchForm || !this.searchToggle) {
      return;
    }
    
    // Toggle active class on search form
    this.searchForm.classList.toggle('is-active');
    
    // Toggle active class on toggle button
    this.searchToggle.classList.toggle('is-active');
    
    // Update aria-expanded
    const isExpanded = this.searchToggle.classList.contains('is-active');
    this.searchToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
    
    // Focus search input if active
    if (isExpanded) {
      const searchInput = this.searchForm.querySelector('input[type="search"]');
      if (searchInput) {
        setTimeout(() => {
          searchInput.focus();
        }, 100);
      }
    }
    
    // Close menu if open
    this.closeMenu();
  }

  /**
   * Close search
   */
  closeSearch() {
    // Skip if no search form
    if (!this.searchForm || !this.searchToggle) {
      return;
    }
    
    // Remove active class from search form
    this.searchForm.classList.remove('is-active');
    
    // Remove active class from toggle button
    this.searchToggle.classList.remove('is-active');
    this.searchToggle.setAttribute('aria-expanded', 'false');
  }

  /**
   * Handle resize
   */
  handleResize() {
    // Check if breakpoint crossed
    const wasMobile = this.isMobile;
    this.isMobile = window.innerWidth < this.mobileBreakpoint;
    
    // Skip if no change
    if (wasMobile === this.isMobile) {
      return;
    }
    
    // Update state
    this.setInitialState();
  }
}

// Export for use in theme.js
export default AquaLuxeNavigation;