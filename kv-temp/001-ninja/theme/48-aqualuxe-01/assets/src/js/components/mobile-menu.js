/**
 * Mobile Menu Functionality
 * 
 * Handles the mobile menu functionality for the AquaLuxe theme.
 */

(function($) {
    'use strict';

    const MobileMenu = {
        /**
         * Initialize the mobile menu functionality
         */
        init: function() {
            this.cacheDom();
            this.bindEvents();
        },

        /**
         * Cache DOM elements
         */
        cacheDom: function() {
            this.$body = $('body');
            this.$mobileMenuToggle = $('.mobile-menu-toggle');
            this.$mobileMenuContainer = $('.mobile-menu-container');
            this.$mobileMenuClose = $('.mobile-menu-close');
            this.$mobileMenuItems = $('.mobile-menu .menu-item-has-children');
            this.mobileMenuActiveClass = 'mobile-menu-active';
            this.submenuOpenClass = 'submenu-open';
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Toggle mobile menu
            this.$mobileMenuToggle.on('click', this.toggleMobileMenu.bind(this));
            
            // Close mobile menu
            this.$mobileMenuClose.on('click', this.closeMobileMenu.bind(this));
            
            // Close menu when clicking outside
            $(document).on('click', this.handleOutsideClick.bind(this));
            
            // Toggle submenu
            this.$mobileMenuItems.children('a').on('click', this.toggleSubmenu.bind(this));
            
            // Handle resize event
            $(window).on('resize', this.handleResize.bind(this));
            
            // Handle escape key
            $(document).on('keyup', this.handleEscapeKey.bind(this));
        },

        /**
         * Toggle mobile menu
         * 
         * @param {Event} e The event object
         */
        toggleMobileMenu: function(e) {
            e.preventDefault();
            
            this.$mobileMenuContainer.toggleClass('active');
            this.$body.toggleClass(this.mobileMenuActiveClass);
            
            // Add no-scroll class to body when menu is open
            if (this.$body.hasClass(this.mobileMenuActiveClass)) {
                this.$body.addClass('no-scroll');
                this.trapFocus();
                
                // Dispatch custom event
                this.dispatchEvent('mobileMenuOpened');
            } else {
                this.$body.removeClass('no-scroll');
                this.releaseFocus();
                
                // Dispatch custom event
                this.dispatchEvent('mobileMenuClosed');
            }
        },

        /**
         * Close mobile menu
         * 
         * @param {Event} e The event object
         */
        closeMobileMenu: function(e) {
            e.preventDefault();
            
            this.$mobileMenuContainer.removeClass('active');
            this.$body.removeClass(this.mobileMenuActiveClass);
            this.$body.removeClass('no-scroll');
            this.releaseFocus();
            
            // Dispatch custom event
            this.dispatchEvent('mobileMenuClosed');
        },

        /**
         * Handle outside click
         * 
         * @param {Event} e The event object
         */
        handleOutsideClick: function(e) {
            if (this.$body.hasClass(this.mobileMenuActiveClass) && 
                !$(e.target).closest(this.$mobileMenuContainer).length && 
                !$(e.target).closest(this.$mobileMenuToggle).length) {
                this.closeMobileMenu(e);
            }
        },

        /**
         * Toggle submenu
         * 
         * @param {Event} e The event object
         */
        toggleSubmenu: function(e) {
            const $parent = $(e.currentTarget).parent();
            
            if (!$parent.hasClass(this.submenuOpenClass)) {
                e.preventDefault();
                
                // Close other open submenus
                $parent.siblings('.' + this.submenuOpenClass).removeClass(this.submenuOpenClass)
                    .find('.' + this.submenuOpenClass).removeClass(this.submenuOpenClass);
                
                $parent.siblings('.' + this.submenuOpenClass).find('> .sub-menu').slideUp(300);
                
                // Open this submenu
                $parent.addClass(this.submenuOpenClass);
                $parent.find('> .sub-menu').slideDown(300);
                
                // Dispatch custom event
                this.dispatchEvent('mobileSubmenuToggled', {
                    item: $parent[0],
                    isOpen: true
                });
            }
        },

        /**
         * Handle resize event
         */
        handleResize: function() {
            // Close mobile menu if window width is greater than 991px
            if (window.innerWidth > 991 && this.$body.hasClass(this.mobileMenuActiveClass)) {
                this.closeMobileMenu({ preventDefault: () => {} });
            }
        },

        /**
         * Handle escape key
         * 
         * @param {Event} e The event object
         */
        handleEscapeKey: function(e) {
            if (e.key === 'Escape' && this.$body.hasClass(this.mobileMenuActiveClass)) {
                this.closeMobileMenu(e);
            }
        },

        /**
         * Trap focus within the mobile menu
         */
        trapFocus: function() {
            // Get all focusable elements
            const focusableElements = this.$mobileMenuContainer.find('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstFocusableElement = focusableElements.first();
            const lastFocusableElement = focusableElements.last();
            
            // Focus the first element
            firstFocusableElement.focus();
            
            // Handle tab key
            this.$mobileMenuContainer.on('keydown.trapFocus', function(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        // Shift + Tab
                        if (document.activeElement === firstFocusableElement[0]) {
                            e.preventDefault();
                            lastFocusableElement.focus();
                        }
                    } else {
                        // Tab
                        if (document.activeElement === lastFocusableElement[0]) {
                            e.preventDefault();
                            firstFocusableElement.focus();
                        }
                    }
                }
            });
        },

        /**
         * Release focus trap
         */
        releaseFocus: function() {
            this.$mobileMenuContainer.off('keydown.trapFocus');
        },

        /**
         * Dispatch custom event
         * 
         * @param {string} eventName The event name
         * @param {Object} detail The event detail
         */
        dispatchEvent: function(eventName, detail = {}) {
            const event = new CustomEvent(eventName, {
                detail: detail
            });
            
            document.dispatchEvent(event);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        MobileMenu.init();
    });

})(jQuery);