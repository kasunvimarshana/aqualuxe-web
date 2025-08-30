/**
 * Navigation Module
 * 
 * Handles navigation functionality including mobile menu, dropdowns, and mega menus
 */

const Navigation = {
    /**
     * Initialize the navigation module
     */
    init() {
        this.setupMobileMenu();
        this.setupDropdowns();
        this.setupMegaMenus();
        this.setupStickyHeader();
    },

    /**
     * Setup mobile menu functionality
     */
    setupMobileMenu() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const overlay = document.querySelector('.mobile-menu-overlay');
        
        if (!mobileMenuToggle || !mobileMenu) {
            return;
        }
        
        // Toggle mobile menu
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('is-active');
            document.body.classList.toggle('mobile-menu-open');
            
            // Toggle aria-expanded attribute
            const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
            mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
            
            // Show overlay
            if (overlay) {
                overlay.classList.toggle('is-active');
            }
        });
        
        // Close mobile menu
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', () => {
                mobileMenu.classList.remove('is-active');
                document.body.classList.remove('mobile-menu-open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                
                if (overlay) {
                    overlay.classList.remove('is-active');
                }
            });
        }
        
        // Close mobile menu when clicking overlay
        if (overlay) {
            overlay.addEventListener('click', () => {
                mobileMenu.classList.remove('is-active');
                document.body.classList.remove('mobile-menu-open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                overlay.classList.remove('is-active');
            });
        }
        
        // Handle sub-menu toggles in mobile menu
        const subMenuToggles = mobileMenu.querySelectorAll('.sub-menu-toggle');
        
        subMenuToggles.forEach(toggle => {
            toggle.addEventListener('click', e => {
                e.preventDefault();
                
                const parent = toggle.closest('li');
                const subMenu = parent.querySelector('.sub-menu');
                
                if (parent && subMenu) {
                    parent.classList.toggle('is-active');
                    
                    // Toggle aria-expanded attribute
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', !isExpanded);
                    
                    // Toggle sub-menu visibility
                    if (subMenu.style.maxHeight) {
                        subMenu.style.maxHeight = null;
                    } else {
                        subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
                    }
                }
            });
        });
        
        // Close mobile menu on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024 && mobileMenu.classList.contains('is-active')) {
                mobileMenu.classList.remove('is-active');
                document.body.classList.remove('mobile-menu-open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                
                if (overlay) {
                    overlay.classList.remove('is-active');
                }
                
                // Reset sub-menus
                const activeSubMenus = mobileMenu.querySelectorAll('.is-active');
                activeSubMenus.forEach(item => {
                    item.classList.remove('is-active');
                    const subMenu = item.querySelector('.sub-menu');
                    if (subMenu) {
                        subMenu.style.maxHeight = null;
                    }
                    
                    const toggle = item.querySelector('.sub-menu-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    },

    /**
     * Setup dropdown menu functionality
     */
    setupDropdowns() {
        const dropdownItems = document.querySelectorAll('.menu-item-has-children:not(.mega-menu)');
        
        dropdownItems.forEach(item => {
            const link = item.querySelector('a');
            const subMenu = item.querySelector('.sub-menu');
            
            if (!link || !subMenu) {
                return;
            }
            
            // Add dropdown toggle button for accessibility
            if (!item.querySelector('.dropdown-toggle')) {
                const toggleButton = document.createElement('button');
                toggleButton.className = 'dropdown-toggle';
                toggleButton.setAttribute('aria-expanded', 'false');
                toggleButton.innerHTML = '<span class="screen-reader-text">Toggle Dropdown</span>';
                link.after(toggleButton);
                
                // Toggle dropdown on button click
                toggleButton.addEventListener('click', e => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
                    
                    // Close all other dropdowns
                    dropdownItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('is-active');
                            const otherToggle = otherItem.querySelector('.dropdown-toggle');
                            if (otherToggle) {
                                otherToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });
                    
                    // Toggle current dropdown
                    item.classList.toggle('is-active');
                    toggleButton.setAttribute('aria-expanded', !isExpanded);
                });
            }
            
            // Handle hover events for desktop
            if (window.innerWidth >= 1024) {
                item.addEventListener('mouseenter', () => {
                    item.classList.add('is-active');
                    const toggle = item.querySelector('.dropdown-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                });
                
                item.addEventListener('mouseleave', () => {
                    item.classList.remove('is-active');
                    const toggle = item.querySelector('.dropdown-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', e => {
            if (!e.target.closest('.menu-item-has-children')) {
                dropdownItems.forEach(item => {
                    item.classList.remove('is-active');
                    const toggle = item.querySelector('.dropdown-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    },

    /**
     * Setup mega menu functionality
     */
    setupMegaMenus() {
        const megaMenuItems = document.querySelectorAll('.mega-menu');
        
        megaMenuItems.forEach(item => {
            const link = item.querySelector('a');
            const megaMenu = item.querySelector('.mega-menu-wrapper');
            
            if (!link || !megaMenu) {
                return;
            }
            
            // Add mega menu toggle button for accessibility
            if (!item.querySelector('.mega-menu-toggle')) {
                const toggleButton = document.createElement('button');
                toggleButton.className = 'mega-menu-toggle';
                toggleButton.setAttribute('aria-expanded', 'false');
                toggleButton.innerHTML = '<span class="screen-reader-text">Toggle Mega Menu</span>';
                link.after(toggleButton);
                
                // Toggle mega menu on button click
                toggleButton.addEventListener('click', e => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
                    
                    // Close all other mega menus
                    megaMenuItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('is-active');
                            const otherToggle = otherItem.querySelector('.mega-menu-toggle');
                            if (otherToggle) {
                                otherToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });
                    
                    // Toggle current mega menu
                    item.classList.toggle('is-active');
                    toggleButton.setAttribute('aria-expanded', !isExpanded);
                });
            }
            
            // Handle hover events for desktop
            if (window.innerWidth >= 1024) {
                item.addEventListener('mouseenter', () => {
                    item.classList.add('is-active');
                    const toggle = item.querySelector('.mega-menu-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                });
                
                item.addEventListener('mouseleave', () => {
                    item.classList.remove('is-active');
                    const toggle = item.querySelector('.mega-menu-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
        
        // Close mega menus when clicking outside
        document.addEventListener('click', e => {
            if (!e.target.closest('.mega-menu')) {
                megaMenuItems.forEach(item => {
                    item.classList.remove('is-active');
                    const toggle = item.querySelector('.mega-menu-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    },

    /**
     * Setup sticky header functionality
     */
    setupStickyHeader() {
        const header = document.querySelector('.site-header');
        
        if (!header || !document.body.classList.contains('sticky-header-enabled')) {
            return;
        }
        
        const headerHeight = header.offsetHeight;
        const headerOffset = header.offsetTop;
        let lastScrollTop = 0;
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Add sticky class when scrolled past header
            if (scrollTop > headerOffset + headerHeight) {
                header.classList.add('is-sticky');
                document.body.style.paddingTop = headerHeight + 'px';
                
                // Hide header when scrolling down, show when scrolling up
                if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
                    header.classList.add('is-hidden');
                } else {
                    header.classList.remove('is-hidden');
                }
            } else {
                header.classList.remove('is-sticky', 'is-hidden');
                document.body.style.paddingTop = '0';
            }
            
            lastScrollTop = scrollTop;
        });
    }
};

export default Navigation;