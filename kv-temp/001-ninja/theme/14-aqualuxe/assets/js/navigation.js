/**
 * AquaLuxe Theme Navigation JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * AquaLuxe Navigation Object
     */
    var AquaLuxeNavigation = {
        /**
         * Initialize
         */
        init: function() {
            this.container = document.getElementById('site-navigation');
            this.button = document.getElementById('mobile-menu-toggle');
            this.menu = document.getElementById('mobile-menu');
            
            this.setupEventListeners();
            this.setupAccessibility();
            this.setupSubmenus();
        },

        /**
         * Setup Event Listeners
         */
        setupEventListeners: function() {
            var self = this;

            // Return early if the navigation doesn't exist.
            if (!this.container || !this.button || !this.menu) {
                return;
            }

            // Toggle mobile menu
            this.button.addEventListener('click', function() {
                self.toggleMenu();
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!self.container.contains(event.target) && !self.menu.classList.contains('hidden')) {
                    self.closeMenu();
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(event) {
                if (event.keyCode === 27 && !self.menu.classList.contains('hidden')) {
                    self.closeMenu();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024 && !self.menu.classList.contains('hidden')) {
                    self.menu.classList.add('hidden');
                    self.button.setAttribute('aria-expanded', 'false');
                }
            });
        },

        /**
         * Setup Accessibility
         */
        setupAccessibility: function() {
            // Return early if the navigation doesn't exist.
            if (!this.container || !this.button || !this.menu) {
                return;
            }

            // Set ARIA attributes
            this.button.setAttribute('aria-expanded', 'false');
            this.button.setAttribute('aria-controls', 'mobile-menu');
        },

        /**
         * Setup Submenus
         */
        setupSubmenus: function() {
            // Return early if the navigation doesn't exist.
            if (!this.container) {
                return;
            }

            // Get all menu items with children
            var menuItems = this.container.querySelectorAll('.menu-item-has-children');

            // Loop through menu items
            for (var i = 0; i < menuItems.length; i++) {
                var menuItem = menuItems[i];
                var link = menuItem.querySelector('a');
                var submenu = menuItem.querySelector('.sub-menu');

                // Skip if no submenu
                if (!submenu) {
                    continue;
                }

                // Create dropdown toggle button
                var toggleButton = document.createElement('button');
                toggleButton.classList.add('submenu-toggle');
                toggleButton.setAttribute('aria-expanded', 'false');
                toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
                toggleButton.setAttribute('aria-label', 'Toggle submenu');

                // Insert toggle button after link
                link.parentNode.insertBefore(toggleButton, link.nextSibling);

                // Add event listener to toggle button
                toggleButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var parent = this.parentNode;
                    var submenu = parent.querySelector('.sub-menu');
                    var expanded = this.getAttribute('aria-expanded') === 'true';

                    // Toggle submenu
                    if (expanded) {
                        this.setAttribute('aria-expanded', 'false');
                        submenu.classList.add('hidden');
                    } else {
                        this.setAttribute('aria-expanded', 'true');
                        submenu.classList.remove('hidden');
                    }
                });

                // Hide submenu by default
                submenu.classList.add('hidden');
            }
        },

        /**
         * Toggle Menu
         */
        toggleMenu: function() {
            // Return early if the navigation doesn't exist.
            if (!this.container || !this.button || !this.menu) {
                return;
            }

            // Toggle menu visibility
            this.menu.classList.toggle('hidden');

            // Update ARIA attributes
            if (this.menu.classList.contains('hidden')) {
                this.button.setAttribute('aria-expanded', 'false');
            } else {
                this.button.setAttribute('aria-expanded', 'true');
            }
        },

        /**
         * Close Menu
         */
        closeMenu: function() {
            // Return early if the navigation doesn't exist.
            if (!this.container || !this.button || !this.menu) {
                return;
            }

            // Hide menu
            this.menu.classList.add('hidden');

            // Update ARIA attributes
            this.button.setAttribute('aria-expanded', 'false');
        }
    };

    // Initialize when the DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        AquaLuxeNavigation.init();
    });

})();