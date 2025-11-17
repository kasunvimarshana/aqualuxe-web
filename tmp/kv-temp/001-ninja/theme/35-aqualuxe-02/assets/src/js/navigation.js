/**
 * AquaLuxe Navigation
 *
 * This file handles the navigation functionality for the AquaLuxe theme.
 */

(function() {
    'use strict';

    /**
     * Navigation
     */
    var AqualuxeNavigation = {
        /**
         * Initialize
         */
        init: function() {
            this.setupMobileMenu();
            this.setupDropdownMenus();
            this.setupMegaMenus();
            this.setupStickyNavigation();
        },

        /**
         * Setup mobile menu
         */
        setupMobileMenu: function() {
            var menuToggle = document.querySelector('.menu-toggle');
            var siteNavigation = document.querySelector('.main-navigation');

            // Return early if the navigation doesn't exist.
            if (!siteNavigation || !menuToggle) {
                return;
            }

            // Add aria-expanded to menu toggle
            menuToggle.setAttribute('aria-expanded', 'false');

            // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
            menuToggle.addEventListener('click', function() {
                siteNavigation.classList.toggle('toggled');

                if (menuToggle.getAttribute('aria-expanded') === 'true') {
                    menuToggle.setAttribute('aria-expanded', 'false');
                } else {
                    menuToggle.setAttribute('aria-expanded', 'true');
                }
            });

            // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
            document.addEventListener('click', function(event) {
                var isClickInside = siteNavigation.contains(event.target) || menuToggle.contains(event.target);

                if (!isClickInside) {
                    siteNavigation.classList.remove('toggled');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Get all the link elements within the menu.
            var links = siteNavigation.getElementsByTagName('a');

            // Toggle focus each time a menu link is focused or blurred.
            for (var i = 0; i < links.length; i++) {
                links[i].addEventListener('focus', toggleFocus, true);
                links[i].addEventListener('blur', toggleFocus, true);
            }

            /**
             * Sets or removes .focus class on an element.
             */
            function toggleFocus() {
                var self = this;

                // Move up through the ancestors of the current link until we hit .nav-menu.
                while (!self.classList.contains('nav-menu')) {
                    // On li elements toggle the class .focus.
                    if ('li' === self.tagName.toLowerCase()) {
                        self.classList.toggle('focus');
                    }
                    self = self.parentNode;
                }
            }

            // Close mobile menu when window is resized
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    siteNavigation.classList.remove('toggled');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        },

        /**
         * Setup dropdown menus
         */
        setupDropdownMenus: function() {
            var menuItems = document.querySelectorAll('.menu-item-has-children');

            // Return early if there are no dropdown menus.
            if (!menuItems.length) {
                return;
            }

            // Add dropdown toggle button to each menu item that has a submenu.
            menuItems.forEach(function(menuItem) {
                var dropdownToggle = document.createElement('button');
                dropdownToggle.className = 'dropdown-toggle';
                dropdownToggle.setAttribute('aria-expanded', 'false');
                dropdownToggle.innerHTML = '<span class="screen-reader-text">Expand child menu</span>';
                menuItem.appendChild(dropdownToggle);

                // Toggle the .toggled-on class and the aria-expanded value each time the button is clicked.
                dropdownToggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    menuItem.classList.toggle('toggled-on');

                    if (dropdownToggle.getAttribute('aria-expanded') === 'true') {
                        dropdownToggle.setAttribute('aria-expanded', 'false');
                    } else {
                        dropdownToggle.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Close dropdown menus when clicking outside.
            document.addEventListener('click', function(event) {
                menuItems.forEach(function(menuItem) {
                    if (!menuItem.contains(event.target)) {
                        menuItem.classList.remove('toggled-on');
                        menuItem.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false');
                    }
                });
            });
        },

        /**
         * Setup mega menus
         */
        setupMegaMenus: function() {
            var megaMenuItems = document.querySelectorAll('.mega-menu');

            // Return early if there are no mega menus.
            if (!megaMenuItems.length) {
                return;
            }

            // Add mega menu functionality.
            megaMenuItems.forEach(function(megaMenuItem) {
                var megaMenuToggle = megaMenuItem.querySelector('a');
                var megaMenuContent = megaMenuItem.querySelector('.mega-menu-content');

                // Return early if there is no mega menu content.
                if (!megaMenuContent) {
                    return;
                }

                // Toggle the .toggled-on class each time the menu item is hovered.
                megaMenuItem.addEventListener('mouseenter', function() {
                    megaMenuItem.classList.add('toggled-on');
                });

                megaMenuItem.addEventListener('mouseleave', function() {
                    megaMenuItem.classList.remove('toggled-on');
                });

                // Toggle the .toggled-on class each time the menu item is clicked.
                megaMenuToggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    megaMenuItem.classList.toggle('toggled-on');
                });
            });
        },

        /**
         * Setup sticky navigation
         */
        setupStickyNavigation: function() {
            var siteHeader = document.querySelector('.site-header');
            var adminBar = document.getElementById('wpadminbar');
            var lastScrollTop = 0;
            var scrollDelta = 10;
            var scrollOffset = 150;

            // Return early if there is no site header.
            if (!siteHeader) {
                return;
            }

            // Check if sticky navigation is enabled.
            if (!document.body.classList.contains('sticky-navigation')) {
                return;
            }

            // Add sticky navigation functionality.
            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                // Check if scroll is more than delta.
                if (Math.abs(lastScrollTop - scrollTop) <= scrollDelta) {
                    return;
                }

                // If scrolled down and past the offset.
                if (scrollTop > lastScrollTop && scrollTop > scrollOffset) {
                    siteHeader.classList.add('site-header-hidden');
                } else {
                    // If scrolled up or above the offset.
                    siteHeader.classList.remove('site-header-hidden');

                    if (scrollTop > 0) {
                        siteHeader.classList.add('site-header-sticky');
                    } else {
                        siteHeader.classList.remove('site-header-sticky');
                    }
                }

                // Adjust for admin bar.
                if (adminBar && window.innerWidth > 600) {
                    siteHeader.style.top = adminBar.offsetHeight + 'px';
                } else {
                    siteHeader.style.top = '0';
                }

                lastScrollTop = scrollTop;
            });
        }
    };

    // Initialize navigation when the DOM is ready.
    document.addEventListener('DOMContentLoaded', function() {
        AqualuxeNavigation.init();
    });
})();