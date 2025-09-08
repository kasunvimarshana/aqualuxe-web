/**
 * AquaLuxe Navigation Module
 *
 * Responsive navigation with accessibility features
 * Mobile menu, dropdowns, and smooth interactions
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Navigation Class
 * 
 * Handles all navigation functionality including mobile menu,
 * dropdowns, keyboard navigation, and accessibility
 */
export class Navigation {
    /**
     * Constructor
     * 
     * @param {Object} config Navigation configuration
     * @param {EventBus} eventBus Event bus instance
     */
    constructor(config = {}, eventBus = null) {
        this.config = {
            debug: false,
            mobileBreakpoint: 768,
            animationDuration: 300,
            dropdownDelay: 150,
            scrollThreshold: 100,
            ...config
        };
        
        this.eventBus = eventBus;
        this.isInitialized = false;
        
        // Navigation elements
        this.nav = null;
        this.mobileToggle = null;
        this.mobileMenu = null;
        this.dropdowns = [];
        this.menuItems = [];
        
        // State
        this.isMobileMenuOpen = false;
        this.activeDropdown = null;
        this.lastScrollY = 0;
        this.isScrolled = false;
        
        // Event handlers (bound methods)
        this.handleMobileToggle = this.handleMobileToggle.bind(this);
        this.handleDropdownToggle = this.handleDropdownToggle.bind(this);
        this.handleKeyDown = this.handleKeyDown.bind(this);
        this.handleClickOutside = this.handleClickOutside.bind(this);
        this.handleScroll = this.handleScroll.bind(this);
        this.handleResize = this.handleResize.bind(this);
        
        this.init();
    }

    /**
     * Initialize navigation
     */
    async init() {
        if (this.isInitialized) {
            return;
        }
        
        try {
            // Find navigation elements
            this.findElements();
            
            if (!this.nav) {
                if (this.config.debug) {
                    console.log('📱 No navigation found');
                }
                return;
            }
            
            // Set up navigation
            this.setupNavigation();
            this.setupMobileMenu();
            this.setupDropdowns();
            this.setupAccessibility();
            this.setupScrollEffects();
            
            // Bind events
            this.bindEvents();
            
            this.isInitialized = true;
            
            // Emit initialization event
            if (this.eventBus) {
                this.eventBus.emit('navigation:initialized', {
                    hasDropdowns: this.dropdowns.length > 0,
                    hasMobileMenu: !!this.mobileMenu
                });
            }
            
            if (this.config.debug) {
                console.log('📱 Navigation initialized');
            }
            
        } catch (error) {
            console.error('❌ Navigation initialization failed:', error);
        }
    }

    /**
     * Find navigation elements
     */
    findElements() {
        // Main navigation
        this.nav = document.querySelector('.site-navigation, .main-navigation, nav[role="navigation"]');
        
        if (!this.nav) {
            return;
        }
        
        // Mobile toggle
        this.mobileToggle = this.nav.querySelector('.menu-toggle, .mobile-toggle, .hamburger');
        
        // Mobile menu
        this.mobileMenu = this.nav.querySelector('.mobile-menu, .nav-menu');
        
        // Dropdown menus
        this.dropdowns = Array.from(this.nav.querySelectorAll('.menu-item-has-children, .has-dropdown'));
        
        // All menu items
        this.menuItems = Array.from(this.nav.querySelectorAll('.menu-item a, .nav-link'));
    }

    /**
     * Set up navigation structure
     */
    setupNavigation() {
        // Add navigation classes
        this.nav.classList.add('aqualuxe-navigation');
        
        // Set ARIA attributes
        this.nav.setAttribute('role', 'navigation');
        if (!this.nav.getAttribute('aria-label')) {
            this.nav.setAttribute('aria-label', 'Primary navigation');
        }
        
        // Add navigation state classes
        this.updateNavigationState();
    }

    /**
     * Set up mobile menu
     */
    setupMobileMenu() {
        if (!this.mobileToggle || !this.mobileMenu) {
            return;
        }
        
        // Set up mobile toggle
        this.mobileToggle.setAttribute('type', 'button');
        this.mobileToggle.setAttribute('aria-controls', 'mobile-menu');
        this.mobileToggle.setAttribute('aria-expanded', 'false');
        this.mobileToggle.setAttribute('aria-label', 'Toggle mobile menu');
        
        // Set up mobile menu
        this.mobileMenu.setAttribute('id', 'mobile-menu');
        this.mobileMenu.setAttribute('aria-hidden', 'true');
        
        // Add hamburger animation if needed
        if (!this.mobileToggle.querySelector('.hamburger-box')) {
            this.createHamburgerIcon();
        }
    }

    /**
     * Create hamburger icon
     */
    createHamburgerIcon() {
        const hamburger = document.createElement('span');
        hamburger.className = 'hamburger-box';
        
        const inner = document.createElement('span');
        inner.className = 'hamburger-inner';
        
        hamburger.appendChild(inner);
        this.mobileToggle.appendChild(hamburger);
    }

    /**
     * Set up dropdown menus
     */
    setupDropdowns() {
        this.dropdowns.forEach((dropdown, index) => {
            const link = dropdown.querySelector('a');
            const submenu = dropdown.querySelector('.sub-menu, .dropdown-menu');
            
            if (!link || !submenu) {
                return;
            }
            
            // Set up dropdown button
            const dropdownId = `dropdown-${index}`;
            
            // Create dropdown toggle if parent link should remain clickable
            if (link.getAttribute('href') && link.getAttribute('href') !== '#') {
                this.createDropdownToggle(dropdown, link, submenu, dropdownId);
            } else {
                // Use the link itself as toggle
                this.setupDropdownLink(link, submenu, dropdownId);
            }
            
            // Set up submenu
            submenu.setAttribute('id', dropdownId);
            submenu.setAttribute('aria-hidden', 'true');
            submenu.classList.add('dropdown-hidden');
            
            // Add dropdown indicators
            this.addDropdownIndicator(dropdown);
        });
    }

    /**
     * Create separate dropdown toggle
     * 
     * @param {Element} dropdown Dropdown container
     * @param {Element} link Main link
     * @param {Element} submenu Submenu element
     * @param {string} dropdownId Dropdown ID
     */
    createDropdownToggle(dropdown, link, submenu, dropdownId) {
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'dropdown-toggle';
        toggle.setAttribute('aria-controls', dropdownId);
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', `Toggle ${link.textContent} submenu`);
        
        // Insert toggle after link
        link.parentNode.insertBefore(toggle, link.nextSibling);
        
        // Bind toggle event
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleDropdown(dropdown, submenu, toggle);
        });
    }

    /**
     * Set up dropdown link
     * 
     * @param {Element} link Dropdown link
     * @param {Element} submenu Submenu element
     * @param {string} dropdownId Dropdown ID
     */
    setupDropdownLink(link, submenu, dropdownId) {
        link.setAttribute('aria-controls', dropdownId);
        link.setAttribute('aria-expanded', 'false');
        link.setAttribute('aria-haspopup', 'true');
        
        // Prevent default if no href
        if (!link.getAttribute('href') || link.getAttribute('href') === '#') {
            link.addEventListener('click', (e) => {
                e.preventDefault();
            });
        }
    }

    /**
     * Add dropdown indicator
     * 
     * @param {Element} dropdown Dropdown container
     */
    addDropdownIndicator(dropdown) {
        if (dropdown.querySelector('.dropdown-indicator')) {
            return;
        }
        
        const indicator = document.createElement('span');
        indicator.className = 'dropdown-indicator';
        indicator.setAttribute('aria-hidden', 'true');
        
        const link = dropdown.querySelector('a');
        if (link) {
            link.appendChild(indicator);
        }
    }

    /**
     * Set up accessibility features
     */
    setupAccessibility() {
        // Add skip link if not present
        this.addSkipLink();
        
        // Set up focus management
        this.setupFocusManagement();
        
        // Add landmark roles
        this.addLandmarkRoles();
    }

    /**
     * Add skip link
     */
    addSkipLink() {
        if (document.querySelector('.skip-link')) {
            return;
        }
        
        const skipLink = document.createElement('a');
        skipLink.className = 'skip-link screen-reader-text';
        skipLink.href = '#main';
        skipLink.textContent = 'Skip to main content';
        
        // Insert at beginning of body
        document.body.insertBefore(skipLink, document.body.firstChild);
    }

    /**
     * Set up focus management
     */
    setupFocusManagement() {
        // Trap focus in mobile menu when open
        this.setupFocusTrap();
        
        // Handle focus styles
        this.setupFocusStyles();
    }

    /**
     * Set up focus trap for mobile menu
     */
    setupFocusTrap() {
        if (!this.mobileMenu) {
            return;
        }
        
        this.mobileMenu.addEventListener('keydown', (e) => {
            if (!this.isMobileMenuOpen || e.key !== 'Tab') {
                return;
            }
            
            const focusableElements = this.getFocusableElements(this.mobileMenu);
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        });
    }

    /**
     * Get focusable elements
     * 
     * @param {Element} container Container element
     * @return {Array} Focusable elements
     */
    getFocusableElements(container) {
        const selector = 'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select';
        return Array.from(container.querySelectorAll(selector)).filter(el => {
            return !el.disabled && !el.getAttribute('aria-hidden') && el.offsetParent !== null;
        });
    }

    /**
     * Set up focus styles
     */
    setupFocusStyles() {
        // Add focus-visible class handling
        this.menuItems.forEach(item => {
            item.addEventListener('focus', () => {
                item.classList.add('focused');
            });
            
            item.addEventListener('blur', () => {
                item.classList.remove('focused');
            });
        });
    }

    /**
     * Add landmark roles
     */
    addLandmarkRoles() {
        // Ensure main content area has proper role
        const main = document.querySelector('main, #main, .main-content');
        if (main && !main.getAttribute('role')) {
            main.setAttribute('role', 'main');
        }
    }

    /**
     * Set up scroll effects
     */
    setupScrollEffects() {
        // Get initial scroll position
        this.lastScrollY = window.scrollY;
        this.updateScrollState();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Mobile toggle
        if (this.mobileToggle) {
            this.mobileToggle.addEventListener('click', this.handleMobileToggle);
        }
        
        // Dropdown interactions
        this.dropdowns.forEach(dropdown => {
            const link = dropdown.querySelector('a');
            const submenu = dropdown.querySelector('.sub-menu, .dropdown-menu');
            
            if (link && submenu) {
                // Click events
                dropdown.addEventListener('click', this.handleDropdownToggle);
                
                // Hover events (desktop only)
                if (window.innerWidth >= this.config.mobileBreakpoint) {
                    this.bindDropdownHover(dropdown, submenu);
                }
            }
        });
        
        // Keyboard navigation
        this.nav.addEventListener('keydown', this.handleKeyDown);
        
        // Click outside to close
        document.addEventListener('click', this.handleClickOutside);
        
        // Scroll events
        window.addEventListener('scroll', this.handleScroll, { passive: true });
        
        // Resize events
        window.addEventListener('resize', this.handleResize);
        
        // App events
        if (this.eventBus) {
            this.eventBus.on('app:resize', this.handleAppResize.bind(this));
        }
    }

    /**
     * Bind dropdown hover events
     * 
     * @param {Element} dropdown Dropdown container
     * @param {Element} submenu Submenu element
     */
    bindDropdownHover(dropdown, submenu) {
        let hoverTimeout;
        
        dropdown.addEventListener('mouseenter', () => {
            clearTimeout(hoverTimeout);
            this.showDropdown(dropdown, submenu);
        });
        
        dropdown.addEventListener('mouseleave', () => {
            hoverTimeout = setTimeout(() => {
                this.hideDropdown(dropdown, submenu);
            }, this.config.dropdownDelay);
        });
    }

    /**
     * Handle mobile toggle click
     * 
     * @param {Event} e Click event
     */
    handleMobileToggle(e) {
        e.preventDefault();
        this.toggleMobileMenu();
    }

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu() {
        this.isMobileMenuOpen = !this.isMobileMenuOpen;
        
        if (this.isMobileMenuOpen) {
            this.openMobileMenu();
        } else {
            this.closeMobileMenu();
        }
    }

    /**
     * Open mobile menu
     */
    openMobileMenu() {
        if (!this.mobileMenu) return;
        
        this.isMobileMenuOpen = true;
        
        // Update attributes
        this.mobileToggle.setAttribute('aria-expanded', 'true');
        this.mobileMenu.setAttribute('aria-hidden', 'false');
        
        // Add classes
        this.nav.classList.add('mobile-menu-open');
        this.mobileMenu.classList.add('is-open');
        document.body.classList.add('mobile-menu-open');
        
        // Focus first menu item
        const firstMenuItem = this.mobileMenu.querySelector('a');
        if (firstMenuItem) {
            firstMenuItem.focus();
        }
        
        // Emit event
        if (this.eventBus) {
            this.eventBus.emit('navigation:mobile-menu:opened');
        }
    }

    /**
     * Close mobile menu
     */
    closeMobileMenu() {
        if (!this.mobileMenu) return;
        
        this.isMobileMenuOpen = false;
        
        // Update attributes
        this.mobileToggle.setAttribute('aria-expanded', 'false');
        this.mobileMenu.setAttribute('aria-hidden', 'true');
        
        // Remove classes
        this.nav.classList.remove('mobile-menu-open');
        this.mobileMenu.classList.remove('is-open');
        document.body.classList.remove('mobile-menu-open');
        
        // Close all dropdowns
        this.closeAllDropdowns();
        
        // Return focus to toggle
        this.mobileToggle.focus();
        
        // Emit event
        if (this.eventBus) {
            this.eventBus.emit('navigation:mobile-menu:closed');
        }
    }

    /**
     * Handle dropdown toggle
     * 
     * @param {Event} e Click event
     */
    handleDropdownToggle(e) {
        const dropdown = e.currentTarget;
        const submenu = dropdown.querySelector('.sub-menu, .dropdown-menu');
        
        if (!submenu) return;
        
        // On mobile or when clicking dropdown toggle
        if (window.innerWidth < this.config.mobileBreakpoint || 
            e.target.classList.contains('dropdown-toggle')) {
            e.preventDefault();
            this.toggleDropdown(dropdown, submenu);
        }
    }

    /**
     * Toggle dropdown
     * 
     * @param {Element} dropdown Dropdown container
     * @param {Element} submenu Submenu element
     * @param {Element} toggle Toggle element
     */
    toggleDropdown(dropdown, submenu, toggle = null) {
        const isOpen = submenu.classList.contains('dropdown-open');
        
        if (isOpen) {
            this.hideDropdown(dropdown, submenu, toggle);
        } else {
            // Close other dropdowns first
            this.closeAllDropdowns();
            this.showDropdown(dropdown, submenu, toggle);
        }
    }

    /**
     * Show dropdown
     * 
     * @param {Element} dropdown Dropdown container
     * @param {Element} submenu Submenu element
     * @param {Element} toggle Toggle element
     */
    showDropdown(dropdown, submenu, toggle = null) {
        const control = toggle || dropdown.querySelector('a');
        
        // Update attributes
        if (control) {
            control.setAttribute('aria-expanded', 'true');
        }
        submenu.setAttribute('aria-hidden', 'false');
        
        // Add classes
        dropdown.classList.add('dropdown-open');
        submenu.classList.remove('dropdown-hidden');
        submenu.classList.add('dropdown-open');
        
        this.activeDropdown = dropdown;
        
        // Emit event
        if (this.eventBus) {
            this.eventBus.emit('navigation:dropdown:opened', { dropdown, submenu });
        }
    }

    /**
     * Hide dropdown
     * 
     * @param {Element} dropdown Dropdown container
     * @param {Element} submenu Submenu element
     * @param {Element} toggle Toggle element
     */
    hideDropdown(dropdown, submenu, toggle = null) {
        const control = toggle || dropdown.querySelector('a');
        
        // Update attributes
        if (control) {
            control.setAttribute('aria-expanded', 'false');
        }
        submenu.setAttribute('aria-hidden', 'true');
        
        // Remove classes
        dropdown.classList.remove('dropdown-open');
        submenu.classList.add('dropdown-hidden');
        submenu.classList.remove('dropdown-open');
        
        if (this.activeDropdown === dropdown) {
            this.activeDropdown = null;
        }
        
        // Emit event
        if (this.eventBus) {
            this.eventBus.emit('navigation:dropdown:closed', { dropdown, submenu });
        }
    }

    /**
     * Close all dropdowns
     */
    closeAllDropdowns() {
        this.dropdowns.forEach(dropdown => {
            const submenu = dropdown.querySelector('.sub-menu, .dropdown-menu');
            if (submenu && submenu.classList.contains('dropdown-open')) {
                this.hideDropdown(dropdown, submenu);
            }
        });
    }

    /**
     * Handle keyboard navigation
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleKeyDown(e) {
        switch (e.key) {
            case 'Escape':
                this.handleEscapeKey(e);
                break;
            case 'Enter':
            case ' ':
                this.handleActivationKey(e);
                break;
            case 'ArrowDown':
                this.handleArrowDown(e);
                break;
            case 'ArrowUp':
                this.handleArrowUp(e);
                break;
            case 'ArrowLeft':
                this.handleArrowLeft(e);
                break;
            case 'ArrowRight':
                this.handleArrowRight(e);
                break;
        }
    }

    /**
     * Handle escape key
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleEscapeKey(e) {
        if (this.isMobileMenuOpen) {
            this.closeMobileMenu();
        } else if (this.activeDropdown) {
            this.closeAllDropdowns();
            const link = this.activeDropdown.querySelector('a');
            if (link) link.focus();
        }
    }

    /**
     * Handle activation keys (Enter/Space)
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleActivationKey(e) {
        const target = e.target;
        
        if (target.classList.contains('dropdown-toggle') || 
            target.closest('.menu-item-has-children')) {
            e.preventDefault();
            
            const dropdown = target.closest('.menu-item-has-children, .has-dropdown');
            if (dropdown) {
                const submenu = dropdown.querySelector('.sub-menu, .dropdown-menu');
                this.toggleDropdown(dropdown, submenu);
            }
        }
    }

    /**
     * Handle arrow down key
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleArrowDown(e) {
        e.preventDefault();
        const currentItem = e.target.closest('.menu-item');
        
        if (currentItem) {
            const nextItem = this.getNextMenuItem(currentItem, 'down');
            if (nextItem) {
                const link = nextItem.querySelector('a');
                if (link) link.focus();
            }
        }
    }

    /**
     * Handle arrow up key
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleArrowUp(e) {
        e.preventDefault();
        const currentItem = e.target.closest('.menu-item');
        
        if (currentItem) {
            const prevItem = this.getNextMenuItem(currentItem, 'up');
            if (prevItem) {
                const link = prevItem.querySelector('a');
                if (link) link.focus();
            }
        }
    }

    /**
     * Handle arrow left key
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleArrowLeft(e) {
        const currentItem = e.target.closest('.menu-item');
        
        // Close submenu if in submenu
        if (currentItem && currentItem.closest('.sub-menu')) {
            e.preventDefault();
            const parentDropdown = currentItem.closest('.menu-item-has-children, .has-dropdown');
            if (parentDropdown) {
                const submenu = parentDropdown.querySelector('.sub-menu, .dropdown-menu');
                this.hideDropdown(parentDropdown, submenu);
                
                const parentLink = parentDropdown.querySelector('a');
                if (parentLink) parentLink.focus();
            }
        }
    }

    /**
     * Handle arrow right key
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleArrowRight(e) {
        const currentItem = e.target.closest('.menu-item');
        
        // Open submenu if has children
        if (currentItem && currentItem.classList.contains('menu-item-has-children')) {
            e.preventDefault();
            const submenu = currentItem.querySelector('.sub-menu, .dropdown-menu');
            if (submenu) {
                this.showDropdown(currentItem, submenu);
                
                const firstSubItem = submenu.querySelector('a');
                if (firstSubItem) firstSubItem.focus();
            }
        }
    }

    /**
     * Get next menu item
     * 
     * @param {Element} currentItem Current menu item
     * @param {string} direction Direction (up/down)
     * @return {Element|null} Next menu item
     */
    getNextMenuItem(currentItem, direction) {
        const menuContainer = currentItem.closest('.menu, .nav-menu');
        if (!menuContainer) return null;
        
        const items = Array.from(menuContainer.children);
        const currentIndex = items.indexOf(currentItem);
        
        if (direction === 'down') {
            return items[currentIndex + 1] || items[0]; // Wrap to first
        } else {
            return items[currentIndex - 1] || items[items.length - 1]; // Wrap to last
        }
    }

    /**
     * Handle click outside navigation
     * 
     * @param {Event} e Click event
     */
    handleClickOutside(e) {
        if (!this.nav.contains(e.target)) {
            if (this.isMobileMenuOpen) {
                this.closeMobileMenu();
            }
            this.closeAllDropdowns();
        }
    }

    /**
     * Handle scroll events
     */
    handleScroll() {
        const currentScrollY = window.scrollY;
        
        // Update scroll state
        this.updateScrollState(currentScrollY);
        
        // Hide dropdowns on scroll
        if (this.activeDropdown) {
            this.closeAllDropdowns();
        }
        
        this.lastScrollY = currentScrollY;
    }

    /**
     * Update scroll state
     * 
     * @param {number} scrollY Current scroll position
     */
    updateScrollState(scrollY = window.scrollY) {
        const wasScrolled = this.isScrolled;
        this.isScrolled = scrollY > this.config.scrollThreshold;
        
        if (this.isScrolled !== wasScrolled) {
            this.updateNavigationState();
            
            // Emit scroll state change event
            if (this.eventBus) {
                this.eventBus.emit('navigation:scroll-state-changed', {
                    isScrolled: this.isScrolled,
                    scrollY
                });
            }
        }
    }

    /**
     * Update navigation state classes
     */
    updateNavigationState() {
        if (this.isScrolled) {
            this.nav.classList.add('is-scrolled');
        } else {
            this.nav.classList.remove('is-scrolled');
        }
        
        // Add mobile/desktop classes based on viewport
        if (window.innerWidth < this.config.mobileBreakpoint) {
            this.nav.classList.add('is-mobile');
            this.nav.classList.remove('is-desktop');
        } else {
            this.nav.classList.add('is-desktop');
            this.nav.classList.remove('is-mobile');
        }
    }

    /**
     * Handle resize events
     */
    handleResize() {
        // Update navigation state
        this.updateNavigationState();
        
        // Close mobile menu if switching to desktop
        if (window.innerWidth >= this.config.mobileBreakpoint && this.isMobileMenuOpen) {
            this.closeMobileMenu();
        }
        
        // Update dropdown hover bindings
        this.updateDropdownBindings();
    }

    /**
     * Handle app resize events
     * 
     * @param {Object} event Event data
     */
    handleAppResize(event) {
        this.handleResize();
    }

    /**
     * Update dropdown hover bindings based on viewport
     */
    updateDropdownBindings() {
        // Remove existing hover listeners and re-add if desktop
        this.dropdowns.forEach(dropdown => {
            const submenu = dropdown.querySelector('.sub-menu, .dropdown-menu');
            if (submenu) {
                // Remove existing listeners by cloning elements
                // (This is a simple way to remove all listeners)
                if (window.innerWidth >= this.config.mobileBreakpoint) {
                    this.bindDropdownHover(dropdown, submenu);
                }
            }
        });
    }

    /**
     * Get navigation state
     * 
     * @return {Object} Navigation state
     */
    getState() {
        return {
            isInitialized: this.isInitialized,
            isMobileMenuOpen: this.isMobileMenuOpen,
            activeDropdown: this.activeDropdown,
            isScrolled: this.isScrolled,
            dropdownCount: this.dropdowns.length,
            menuItemCount: this.menuItems.length
        };
    }

    /**
     * Cleanup navigation
     */
    cleanup() {
        // Remove event listeners
        if (this.mobileToggle) {
            this.mobileToggle.removeEventListener('click', this.handleMobileToggle);
        }
        
        this.nav.removeEventListener('keydown', this.handleKeyDown);
        document.removeEventListener('click', this.handleClickOutside);
        window.removeEventListener('scroll', this.handleScroll);
        window.removeEventListener('resize', this.handleResize);
        
        // Close mobile menu
        if (this.isMobileMenuOpen) {
            this.closeMobileMenu();
        }
        
        // Close dropdowns
        this.closeAllDropdowns();
        
        // Remove classes
        document.body.classList.remove('mobile-menu-open');
        
        if (this.config.debug) {
            console.log('📱 Navigation cleaned up');
        }
    }
}

// Export for module loader
export default Navigation;
