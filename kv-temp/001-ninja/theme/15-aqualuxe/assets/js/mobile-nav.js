/**
 * AquaLuxe Mobile Navigation
 * 
 * Handles mobile navigation functionality
 * 
 * @package AquaLuxe
 */
(function() {
    'use strict';

    // DOM elements
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const body = document.body;

    /**
     * Initialize mobile navigation
     */
    function initMobileNav() {
        if (!menuToggle || !mainNavigation) {
            return;
        }

        // Toggle mobile menu
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle active class on menu toggle
            menuToggle.classList.toggle('active');
            
            // Toggle active class on main navigation
            mainNavigation.classList.toggle('active');
            
            // Toggle aria-expanded attribute
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            
            // Toggle body class to prevent scrolling
            body.classList.toggle('mobile-menu-active');
        });

        // Handle dropdown toggles
        if (dropdownToggles.length > 0) {
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get parent menu item
                    const menuItem = toggle.parentNode;
                    
                    // Toggle active class on menu item
                    menuItem.classList.toggle('active');
                    
                    // Toggle active class on dropdown toggle
                    toggle.classList.toggle('active');
                    
                    // Toggle aria-expanded attribute
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', !isExpanded);
                });
            });
        }

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                // Reset mobile menu
                menuToggle.classList.remove('active');
                mainNavigation.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                body.classList.remove('mobile-menu-active');
                
                // Reset dropdown toggles
                dropdownToggles.forEach(function(toggle) {
                    toggle.parentNode.classList.remove('active');
                    toggle.classList.remove('active');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            }
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mainNavigation.classList.contains('active')) {
                menuToggle.classList.remove('active');
                mainNavigation.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                body.classList.remove('mobile-menu-active');
                
                // Focus on menu toggle
                menuToggle.focus();
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (mainNavigation.classList.contains('active') && 
                !mainNavigation.contains(e.target) && 
                !menuToggle.contains(e.target)) {
                menuToggle.classList.remove('active');
                mainNavigation.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                body.classList.remove('mobile-menu-active');
            }
        });

        // Add touch support for dropdown menus on mobile
        const menuItems = document.querySelectorAll('.menu-item-has-children > a');
        
        if (menuItems.length > 0) {
            menuItems.forEach(function(item) {
                item.addEventListener('touchstart', function(e) {
                    const parent = item.parentNode;
                    
                    // Check if this is a mobile view
                    if (window.innerWidth < 992) {
                        // If the parent doesn't have the active class, prevent default and add active class
                        if (!parent.classList.contains('active')) {
                            e.preventDefault();
                            
                            // Remove active class from all other menu items
                            document.querySelectorAll('.menu-item-has-children').forEach(function(menuItem) {
                                if (menuItem !== parent) {
                                    menuItem.classList.remove('active');
                                }
                            });
                            
                            // Add active class to this menu item
                            parent.classList.add('active');
                        }
                    }
                });
            });
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', initMobileNav);
})();