/**
 * Navigation Module
 * Handles navigation functionality including mobile menu, mega menu, and sticky header
 */

class Navigation {
    constructor() {
        this.mobileMenuOpen = false;
        this.init();
    }
    
    init() {
        this.initMobileMenu();
        this.initMegaMenu();
        this.initStickyHeader();
        this.initDropdowns();
        this.initKeyboardNavigation();
    }
    
    /**
     * Initialize mobile menu functionality
     */
    initMobileMenu() {
        const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        const overlay = document.querySelector('[data-mobile-menu-overlay]');
        const closeButton = document.querySelector('[data-mobile-menu-close]');
        
        if (!toggleButton || !mobileMenu) return;
        
        // Toggle button click
        toggleButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleMobileMenu();
        });
        
        // Close button click
        if (closeButton) {
            closeButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeMobileMenu();
            });
        }
        
        // Overlay click
        if (overlay) {
            overlay.addEventListener('click', () => {
                this.closeMobileMenu();
            });
        }
        
        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.mobileMenuOpen) {
                this.closeMobileMenu();
            }
        });
        
        // Submenu toggles
        this.initMobileSubmenus();
    }
    
    /**
     * Initialize mobile submenus
     */
    initMobileSubmenus() {
        const submenuToggles = document.querySelectorAll('.mobile-nav-menu .submenu-toggle');
        
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const submenu = toggle.nextElementSibling;
                if (submenu) {
                    submenu.classList.toggle('is-open');
                    toggle.classList.toggle('is-open');
                }
            });
        });
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
        const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
        
        if (!mobileMenu) return;
        
        this.mobileMenuOpen = true;
        mobileMenu.classList.add('is-open');
        document.body.classList.add('mobile-menu-open');
        
        // Update toggle button
        if (toggleButton) {
            toggleButton.classList.add('is-active');
            toggleButton.setAttribute('aria-expanded', 'true');
        }
        
        // Focus management
        const firstFocusable = mobileMenu.querySelector('a, button, input, [tabindex]');
        if (firstFocusable) {
            setTimeout(() => firstFocusable.focus(), 100);
        }
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    /**
     * Close mobile menu
     */
    closeMobileMenu() {
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
        
        if (!mobileMenu) return;
        
        this.mobileMenuOpen = false;
        mobileMenu.classList.remove('is-open');
        document.body.classList.remove('mobile-menu-open');
        
        // Update toggle button
        if (toggleButton) {
            toggleButton.classList.remove('is-active');
            toggleButton.setAttribute('aria-expanded', 'false');
            toggleButton.focus();
        }
        
        // Restore body scroll
        document.body.style.overflow = '';
        
        // Close all submenus
        const openSubmenus = mobileMenu.querySelectorAll('.sub-menu.is-open');
        openSubmenus.forEach(submenu => {
            submenu.classList.remove('is-open');
        });
        
        const openToggles = mobileMenu.querySelectorAll('.submenu-toggle.is-open');
        openToggles.forEach(toggle => {
            toggle.classList.remove('is-open');
        });
    }
    
    /**
     * Initialize mega menu functionality
     */
    initMegaMenu() {
        const megaMenuItems = document.querySelectorAll('.menu-item-has-children');
        
        megaMenuItems.forEach(item => {
            const trigger = item.querySelector('> a');
            const submenu = item.querySelector('.sub-menu');
            
            if (!trigger || !submenu) return;
            
            let hoverTimeout;
            
            // Mouse events
            item.addEventListener('mouseenter', () => {
                clearTimeout(hoverTimeout);
                this.openSubmenu(submenu);
            });
            
            item.addEventListener('mouseleave', () => {
                hoverTimeout = setTimeout(() => {
                    this.closeSubmenu(submenu);
                }, 150);
            });
            
            // Keyboard events
            trigger.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleSubmenu(submenu);
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.openSubmenu(submenu);
                    const firstLink = submenu.querySelector('a');
                    if (firstLink) firstLink.focus();
                }
            });
        });
    }
    
    /**
     * Open submenu
     */
    openSubmenu(submenu) {
        submenu.classList.add('is-open');
        submenu.setAttribute('aria-hidden', 'false');
    }
    
    /**
     * Close submenu
     */
    closeSubmenu(submenu) {
        submenu.classList.remove('is-open');
        submenu.setAttribute('aria-hidden', 'true');
    }
    
    /**
     * Toggle submenu
     */
    toggleSubmenu(submenu) {
        if (submenu.classList.contains('is-open')) {
            this.closeSubmenu(submenu);
        } else {
            this.openSubmenu(submenu);
        }
    }
    
    /**
     * Initialize sticky header
     */
    initStickyHeader() {
        const header = document.querySelector('.site-header');
        if (!header) return;
        
        let lastScrollY = window.scrollY;
        let ticking = false;
        
        const updateHeader = () => {
            const scrollY = window.scrollY;
            const scrollDirection = scrollY > lastScrollY ? 'down' : 'up';
            
            // Add scrolled class when scrolled
            if (scrollY > 100) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
            
            // Hide header when scrolling down
            if (scrollY > 200 && scrollDirection === 'down') {
                header.classList.add('is-hidden');
            } else if (scrollDirection === 'up') {
                header.classList.remove('is-hidden');
            }
            
            lastScrollY = scrollY;
            ticking = false;
        };
        
        const requestTick = () => {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        };
        
        window.addEventListener('scroll', requestTick, { passive: true });
        
        // Initial call
        updateHeader();
    }
    
    /**
     * Initialize dropdown functionality
     */
    initDropdowns() {
        const dropdowns = document.querySelectorAll('[data-dropdown]');
        
        dropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            
            if (!trigger || !menu) return;
            
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleDropdown(dropdown);
            });
            
            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    this.closeDropdown(dropdown);
                }
            });
            
            // Keyboard navigation
            trigger.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleDropdown(dropdown);
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.openDropdown(dropdown);
                    const firstItem = menu.querySelector('a, button');
                    if (firstItem) firstItem.focus();
                }
            });
        });
    }
    
    /**
     * Toggle dropdown
     */
    toggleDropdown(dropdown) {
        if (dropdown.classList.contains('is-open')) {
            this.closeDropdown(dropdown);
        } else {
            this.openDropdown(dropdown);
        }
    }
    
    /**
     * Open dropdown
     */
    openDropdown(dropdown) {
        // Close other dropdowns
        const openDropdowns = document.querySelectorAll('[data-dropdown].is-open');
        openDropdowns.forEach(otherDropdown => {
            if (otherDropdown !== dropdown) {
                this.closeDropdown(otherDropdown);
            }
        });
        
        dropdown.classList.add('is-open');
        const trigger = dropdown.querySelector('[data-dropdown-trigger]');
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        
        if (trigger) trigger.setAttribute('aria-expanded', 'true');
        if (menu) menu.setAttribute('aria-hidden', 'false');
    }
    
    /**
     * Close dropdown
     */
    closeDropdown(dropdown) {
        dropdown.classList.remove('is-open');
        const trigger = dropdown.querySelector('[data-dropdown-trigger]');
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
        if (menu) menu.setAttribute('aria-hidden', 'true');
    }
    
    /**
     * Initialize keyboard navigation
     */
    initKeyboardNavigation() {
        // Handle escape key for closing menus
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                // Close open dropdowns
                const openDropdowns = document.querySelectorAll('[data-dropdown].is-open');
                openDropdowns.forEach(dropdown => this.closeDropdown(dropdown));
                
                // Close open submenus
                const openSubmenus = document.querySelectorAll('.sub-menu.is-open');
                openSubmenus.forEach(submenu => this.closeSubmenu(submenu));
            }
        });
        
        // Arrow key navigation in menus
        this.initArrowKeyNavigation();
    }
    
    /**
     * Initialize arrow key navigation
     */
    initArrowKeyNavigation() {
        const menus = document.querySelectorAll('.nav-menu, .sub-menu, [data-dropdown-menu]');
        
        menus.forEach(menu => {
            const items = menu.querySelectorAll('a, button');
            
            items.forEach((item, index) => {
                item.addEventListener('keydown', (e) => {
                    let targetIndex;
                    
                    switch (e.key) {
                        case 'ArrowDown':
                            e.preventDefault();
                            targetIndex = (index + 1) % items.length;
                            break;
                        case 'ArrowUp':
                            e.preventDefault();
                            targetIndex = (index - 1 + items.length) % items.length;
                            break;
                        case 'Home':
                            e.preventDefault();
                            targetIndex = 0;
                            break;
                        case 'End':
                            e.preventDefault();
                            targetIndex = items.length - 1;
                            break;
                        default:
                            return;
                    }
                    
                    items[targetIndex].focus();
                });
            });
        });
    }
}

export default Navigation;