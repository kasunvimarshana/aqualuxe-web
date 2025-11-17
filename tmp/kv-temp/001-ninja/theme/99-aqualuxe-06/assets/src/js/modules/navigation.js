/**
 * Navigation Module
 * 
 * Handles main navigation, mobile menu, and navigation interactions
 * 
 * @package AquaLuxe
 * @since 2.0.0
 */

export class Navigation {
    constructor(config = {}) {
        this.config = {
            mobileBreakpoint: 768,
            debug: false,
            ...config
        };

        this.eventBus = config.eventBus || null;
        this.mobileMenuOpen = false;
        
        this.init();
    }

    /**
     * Initialize navigation
     */
    init() {
        this.setupMobileMenu();
        this.setupDropdowns();
        this.setupAccessibility();
        
        if (this.config.debug) {
            console.log('🧭 Navigation module initialized');
        }
    }

    /**
     * Setup mobile menu functionality
     */
    setupMobileMenu() {
        const mobileToggle = document.querySelector('[data-mobile-menu-toggle]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        
        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                this.toggleMobileMenu();
            });

            // Close menu on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.mobileMenuOpen) {
                    this.closeMobileMenu();
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (this.mobileMenuOpen && !mobileMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                    this.closeMobileMenu();
                }
            });
        }
    }

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu() {
        if (this.mobileMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    /**
     * Open mobile menu
     */
    openMobileMenu() {
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        const mobileToggle = document.querySelector('[data-mobile-menu-toggle]');
        
        if (mobileMenu) {
            mobileMenu.classList.add('is-open');
            document.body.classList.add('mobile-menu-open');
            
            if (mobileToggle) {
                mobileToggle.setAttribute('aria-expanded', 'true');
            }
            
            this.mobileMenuOpen = true;
            
            if (this.eventBus) {
                this.eventBus.emit('navigation:mobile-menu-opened');
            }
        }
    }

    /**
     * Close mobile menu
     */
    closeMobileMenu() {
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        const mobileToggle = document.querySelector('[data-mobile-menu-toggle]');
        
        if (mobileMenu) {
            mobileMenu.classList.remove('is-open');
            document.body.classList.remove('mobile-menu-open');
            
            if (mobileToggle) {
                mobileToggle.setAttribute('aria-expanded', 'false');
            }
            
            this.mobileMenuOpen = false;
            
            if (this.eventBus) {
                this.eventBus.emit('navigation:mobile-menu-closed');
            }
        }
    }

    /**
     * Setup dropdown menus
     */
    setupDropdowns() {
        const dropdownTriggers = document.querySelectorAll('[data-dropdown-trigger]');
        
        dropdownTriggers.forEach(trigger => {
            const dropdown = trigger.nextElementSibling;
            
            if (dropdown && dropdown.hasAttribute('data-dropdown')) {
                // Toggle on click
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleDropdown(trigger, dropdown);
                });

                // Handle keyboard navigation
                trigger.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowDown' || e.key === 'Enter') {
                        e.preventDefault();
                        this.openDropdown(trigger, dropdown);
                        this.focusFirstDropdownItem(dropdown);
                    }
                });
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            dropdownTriggers.forEach(trigger => {
                const dropdown = trigger.nextElementSibling;
                if (dropdown && !trigger.contains(e.target) && !dropdown.contains(e.target)) {
                    this.closeDropdown(trigger, dropdown);
                }
            });
        });
    }

    /**
     * Toggle dropdown
     */
    toggleDropdown(trigger, dropdown) {
        if (dropdown.classList.contains('is-open')) {
            this.closeDropdown(trigger, dropdown);
        } else {
            this.openDropdown(trigger, dropdown);
        }
    }

    /**
     * Open dropdown
     */
    openDropdown(trigger, dropdown) {
        dropdown.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        
        if (this.eventBus) {
            this.eventBus.emit('navigation:dropdown-opened', { trigger, dropdown });
        }
    }

    /**
     * Close dropdown
     */
    closeDropdown(trigger, dropdown) {
        dropdown.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
        
        if (this.eventBus) {
            this.eventBus.emit('navigation:dropdown-closed', { trigger, dropdown });
        }
    }

    /**
     * Focus first dropdown item
     */
    focusFirstDropdownItem(dropdown) {
        const firstLink = dropdown.querySelector('a, button');
        if (firstLink) {
            firstLink.focus();
        }
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Add ARIA attributes
        const menuItems = document.querySelectorAll('.menu-item-has-children > a');
        menuItems.forEach(item => {
            item.setAttribute('aria-haspopup', 'true');
            item.setAttribute('aria-expanded', 'false');
        });
    }
}