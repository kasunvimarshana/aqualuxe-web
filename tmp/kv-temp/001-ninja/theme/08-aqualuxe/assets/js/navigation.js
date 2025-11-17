/**
 * AquaLuxe Theme Navigation JavaScript
 * 
 * Handles the navigation menu functionality
 */

(function() {
    'use strict';

    /**
     * Initialize navigation functionality
     */
    function init() {
        setupMobileMenu();
        setupDropdownMenus();
        setupMegaMenu();
        setupStickyNavigation();
        setupAccessibility();
    }

    /**
     * Mobile Menu functionality
     */
    function setupMobileMenu() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mainNavigation = document.querySelector('.main-navigation');
        const body = document.body;
        
        if (!mobileMenuToggle || !mainNavigation) return;
        
        mobileMenuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            this.setAttribute('aria-expanded', !expanded);
            mainNavigation.classList.toggle('menu-open');
            body.classList.toggle('menu-is-open');
            
            // Prevent scrolling when menu is open
            if (!expanded) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (mainNavigation.classList.contains('menu-open') && 
                !mainNavigation.contains(event.target) && 
                !mobileMenuToggle.contains(event.target)) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mainNavigation.classList.remove('menu-open');
                body.classList.remove('menu-is-open');
                body.style.overflow = '';
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && mainNavigation.classList.contains('menu-open')) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mainNavigation.classList.remove('menu-open');
                body.classList.remove('menu-is-open');
                body.style.overflow = '';
                mobileMenuToggle.focus();
            }
        });
    }

    /**
     * Dropdown Menus functionality
     */
    function setupDropdownMenus() {
        const menuItems = document.querySelectorAll('.menu-item-has-children');
        if (!menuItems.length) return;

        // Add dropdown toggle buttons
        menuItems.forEach(function(menuItem) {
            // Skip if toggle already exists
            if (menuItem.querySelector('.dropdown-toggle')) return;
            
            const link = menuItem.querySelector('a');
            const dropdownToggle = document.createElement('button');
            dropdownToggle.className = 'dropdown-toggle';
            dropdownToggle.setAttribute('aria-expanded', 'false');
            dropdownToggle.innerHTML = '<span class="screen-reader-text">Expand submenu</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            
            link.after(dropdownToggle);
            
            // Toggle submenu on click
            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                this.setAttribute('aria-expanded', !expanded);
                this.classList.toggle('toggled-on');
                menuItem.classList.toggle('submenu-open');
                
                // Close other open submenus
                if (!expanded) {
                    menuItems.forEach(function(item) {
                        if (item !== menuItem && item.classList.contains('submenu-open')) {
                            item.classList.remove('submenu-open');
                            const toggle = item.querySelector('.dropdown-toggle');
                            if (toggle) {
                                toggle.setAttribute('aria-expanded', 'false');
                                toggle.classList.remove('toggled-on');
                            }
                        }
                    });
                }
            });
        });
        
        // Handle hover events on desktop
        function handleHoverEvents() {
            if (window.innerWidth >= 1024) {
                menuItems.forEach(function(menuItem) {
                    menuItem.addEventListener('mouseenter', function() {
                        this.classList.add('submenu-open');
                        const toggle = this.querySelector('.dropdown-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'true');
                            toggle.classList.add('toggled-on');
                        }
                    });
                    
                    menuItem.addEventListener('mouseleave', function() {
                        this.classList.remove('submenu-open');
                        const toggle = this.querySelector('.dropdown-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'false');
                            toggle.classList.remove('toggled-on');
                        }
                    });
                });
            }
        }
        
        // Initialize hover events
        handleHoverEvents();
        
        // Update on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleHoverEvents, 250);
        });
        
        // Close all submenus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.menu-item-has-children')) {
                menuItems.forEach(function(menuItem) {
                    menuItem.classList.remove('submenu-open');
                    const toggle = menuItem.querySelector('.dropdown-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.classList.remove('toggled-on');
                    }
                });
            }
        });
    }

    /**
     * Mega Menu functionality
     */
    function setupMegaMenu() {
        const megaMenuItems = document.querySelectorAll('.menu-item-has-mega-menu');
        if (!megaMenuItems.length) return;

        megaMenuItems.forEach(function(menuItem) {
            const link = menuItem.querySelector('a');
            const megaMenu = menuItem.querySelector('.mega-menu');
            
            if (!link || !megaMenu) return;
            
            // Add toggle button if not already present
            if (!menuItem.querySelector('.mega-menu-toggle')) {
                const megaMenuToggle = document.createElement('button');
                megaMenuToggle.className = 'mega-menu-toggle';
                megaMenuToggle.setAttribute('aria-expanded', 'false');
                megaMenuToggle.innerHTML = '<span class="screen-reader-text">Expand mega menu</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
                
                link.after(megaMenuToggle);
                
                // Toggle mega menu on click
                megaMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                    this.setAttribute('aria-expanded', !expanded);
                    this.classList.toggle('toggled-on');
                    menuItem.classList.toggle('mega-menu-open');
                    
                    // Close other open mega menus
                    if (!expanded) {
                        megaMenuItems.forEach(function(item) {
                            if (item !== menuItem && item.classList.contains('mega-menu-open')) {
                                item.classList.remove('mega-menu-open');
                                const toggle = item.querySelector('.mega-menu-toggle');
                                if (toggle) {
                                    toggle.setAttribute('aria-expanded', 'false');
                                    toggle.classList.remove('toggled-on');
                                }
                            }
                        });
                    }
                });
            }
        });
        
        // Handle hover events on desktop
        function handleMegaMenuHover() {
            if (window.innerWidth >= 1024) {
                megaMenuItems.forEach(function(menuItem) {
                    menuItem.addEventListener('mouseenter', function() {
                        this.classList.add('mega-menu-open');
                        const toggle = this.querySelector('.mega-menu-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'true');
                            toggle.classList.add('toggled-on');
                        }
                    });
                    
                    menuItem.addEventListener('mouseleave', function() {
                        this.classList.remove('mega-menu-open');
                        const toggle = this.querySelector('.mega-menu-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'false');
                            toggle.classList.remove('toggled-on');
                        }
                    });
                });
            }
        }
        
        // Initialize hover events
        handleMegaMenuHover();
        
        // Update on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleMegaMenuHover, 250);
        });
        
        // Close all mega menus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.menu-item-has-mega-menu')) {
                megaMenuItems.forEach(function(menuItem) {
                    menuItem.classList.remove('mega-menu-open');
                    const toggle = menuItem.querySelector('.mega-menu-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.classList.remove('toggled-on');
                    }
                });
            }
        });
    }

    /**
     * Sticky Navigation functionality
     */
    function setupStickyNavigation() {
        const header = document.getElementById('masthead');
        if (!header) return;

        const headerHeight = header.offsetHeight;
        let lastScrollTop = 0;
        const scrollThreshold = 100;
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Add sticky class when scrolling down past threshold
            if (scrollTop > scrollThreshold) {
                header.classList.add('sticky-header');
                document.body.style.paddingTop = headerHeight + 'px';
            } else {
                header.classList.remove('sticky-header');
                document.body.style.paddingTop = '0';
            }
            
            // Hide header when scrolling down, show when scrolling up
            if (scrollTop > lastScrollTop && scrollTop > headerHeight + 200) {
                header.classList.add('header-hidden');
            } else {
                header.classList.remove('header-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    }

    /**
     * Accessibility improvements
     */
    function setupAccessibility() {
        // Add aria attributes to menu items
        const menuItems = document.querySelectorAll('.menu-item-has-children, .menu-item-has-mega-menu');
        
        menuItems.forEach(function(menuItem) {
            const link = menuItem.querySelector('a');
            const submenu = menuItem.querySelector('.sub-menu, .mega-menu');
            
            if (link && submenu) {
                // Generate unique ID for submenu
                const submenuId = 'submenu-' + Math.random().toString(36).substr(2, 9);
                submenu.id = submenuId;
                
                // Add aria attributes
                link.setAttribute('aria-haspopup', 'true');
                link.setAttribute('aria-expanded', 'false');
                link.setAttribute('aria-controls', submenuId);
                
                // Update aria-expanded when submenu is toggled
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const isOpen = menuItem.classList.contains('submenu-open') || 
                                          menuItem.classList.contains('mega-menu-open');
                            link.setAttribute('aria-expanded', isOpen);
                        }
                    });
                });
                
                observer.observe(menuItem, { attributes: true });
            }
        });
        
        // Add keyboard navigation
        const allLinks = document.querySelectorAll('.menu a');
        
        allLinks.forEach(function(link, index) {
            link.addEventListener('keydown', function(e) {
                const parentItem = link.parentNode;
                
                // Open submenu on arrow down
                if (e.key === 'ArrowDown' && 
                    (parentItem.classList.contains('menu-item-has-children') || 
                     parentItem.classList.contains('menu-item-has-mega-menu'))) {
                    e.preventDefault();
                    parentItem.classList.add('submenu-open');
                    
                    // Focus first link in submenu
                    const submenu = parentItem.querySelector('.sub-menu, .mega-menu');
                    if (submenu) {
                        const firstLink = submenu.querySelector('a');
                        if (firstLink) {
                            firstLink.focus();
                        }
                    }
                }
                
                // Close submenu on arrow up
                if (e.key === 'ArrowUp' && 
                    parentItem.parentNode.classList.contains('sub-menu')) {
                    e.preventDefault();
                    const parentMenuItem = parentItem.parentNode.parentNode;
                    parentMenuItem.classList.remove('submenu-open');
                    
                    // Focus parent link
                    const parentLink = parentMenuItem.querySelector('a');
                    if (parentLink) {
                        parentLink.focus();
                    }
                }
                
                // Navigate to next item on arrow right
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    const nextItem = parentItem.nextElementSibling;
                    if (nextItem) {
                        const nextLink = nextItem.querySelector('a');
                        if (nextLink) {
                            nextLink.focus();
                        }
                    }
                }
                
                // Navigate to previous item on arrow left
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const prevItem = parentItem.previousElementSibling;
                    if (prevItem) {
                        const prevLink = prevItem.querySelector('a');
                        if (prevLink) {
                            prevLink.focus();
                        }
                    }
                }
                
                // Close submenu on escape
                if (e.key === 'Escape') {
                    const parentSubmenu = parentItem.parentNode;
                    if (parentSubmenu.classList.contains('sub-menu') || 
                        parentSubmenu.classList.contains('mega-menu')) {
                        e.preventDefault();
                        const parentMenuItem = parentSubmenu.parentNode;
                        parentMenuItem.classList.remove('submenu-open');
                        parentMenuItem.classList.remove('mega-menu-open');
                        
                        // Focus parent link
                        const parentLink = parentMenuItem.querySelector('a');
                        if (parentLink) {
                            parentLink.focus();
                        }
                    }
                }
            });
        });
    }

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();