/**
 * AquaLuxe Theme Main JavaScript
 * 
 * This file contains the core JavaScript functionality for the AquaLuxe theme.
 */

// Use IIFE to avoid polluting global namespace
(function() {
    'use strict';

    // DOM ready function
    function domReady(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    // Initialize all modules
    function initModules() {
        // Initialize navigation
        Navigation.init();
        
        // Initialize dark mode
        if (typeof DarkMode !== 'undefined') {
            DarkMode.init();
        }
        
        // Initialize WooCommerce features if WooCommerce is active
        if (typeof WooCommerce !== 'undefined') {
            WooCommerce.init();
        }
    }

    // Navigation functionality
    const Navigation = {
        init: function() {
            this.mobileToggle();
            this.subMenuToggle();
            this.stickyHeader();
        },
        
        mobileToggle: function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (!menuToggle || !mobileMenu) return;
            
            menuToggle.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                
                this.setAttribute('aria-expanded', !expanded);
                mobileMenu.classList.toggle('active');
                document.body.classList.toggle('mobile-menu-open');
                
                if (!expanded) {
                    // Trap focus inside mobile menu when open
                    mobileMenu.setAttribute('aria-hidden', 'false');
                } else {
                    mobileMenu.setAttribute('aria-hidden', 'true');
                }
            });
        },
        
        subMenuToggle: function() {
            const subMenuToggles = document.querySelectorAll('.menu-item-has-children > a');
            
            subMenuToggles.forEach(toggle => {
                // Create dropdown toggle button
                const dropdownToggle = document.createElement('button');
                dropdownToggle.className = 'dropdown-toggle';
                dropdownToggle.setAttribute('aria-expanded', 'false');
                dropdownToggle.innerHTML = '<span class="screen-reader-text">Toggle submenu</span>';
                
                toggle.parentNode.insertBefore(dropdownToggle, toggle.nextSibling);
                
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                    
                    this.setAttribute('aria-expanded', !expanded);
                    this.parentNode.classList.toggle('submenu-open');
                    
                    const subMenu = this.parentNode.querySelector('.sub-menu');
                    if (subMenu) {
                        subMenu.setAttribute('aria-hidden', expanded);
                    }
                });
            });
        },
        
        stickyHeader: function() {
            const header = document.querySelector('.site-header');
            if (!header) return;
            
            let lastScrollTop = 0;
            const scrollThreshold = 100;
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > scrollThreshold) {
                    header.classList.add('sticky');
                    
                    // Hide header when scrolling down, show when scrolling up
                    if (scrollTop > lastScrollTop) {
                        header.classList.add('header-hidden');
                    } else {
                        header.classList.remove('header-hidden');
                    }
                } else {
                    header.classList.remove('sticky', 'header-hidden');
                }
                
                lastScrollTop = scrollTop;
            });
        }
    };

    // Initialize everything when DOM is ready
    domReady(function() {
        initModules();
    });
})();