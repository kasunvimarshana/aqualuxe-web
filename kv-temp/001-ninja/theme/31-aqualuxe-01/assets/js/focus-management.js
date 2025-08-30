/**
 * File focus-management.js.
 *
 * Handles focus management for modals, dropdowns, and other interactive elements.
 */
(function($) {
    'use strict';

    // Variables
    var $body = $('body');
    var $searchToggle = $('#search-toggle');
    var $searchOverlay = $('#search-overlay');
    var $searchClose = $('#search-close');
    var $searchInput = $('.search-field');
    var $mobileMenuToggle = $('#mobile-menu-toggle');
    var $mobileMenu = $('#mobile-menu');
    var $dropdownToggles = $('.dropdown-toggle');
    var previousFocus = null;

    // Initialize
    function init() {
        setupSearchOverlayFocus();
        setupMobileMenuFocus();
        setupDropdownFocus();
        setupQuickViewModalFocus();
    }

    // Setup search overlay focus management
    function setupSearchOverlayFocus() {
        // Store previous focus and set focus to search input when search overlay is opened
        $searchToggle.on('click', function() {
            previousFocus = document.activeElement;
            setTimeout(function() {
                $searchInput.focus();
            }, 100);
        });

        // Restore focus when search overlay is closed
        $searchClose.on('click', function() {
            setTimeout(function() {
                if (previousFocus) {
                    previousFocus.focus();
                }
            }, 100);
        });
    }

    // Setup mobile menu focus management
    function setupMobileMenuFocus() {
        // Store previous focus when mobile menu is opened
        $mobileMenuToggle.on('click', function() {
            var isExpanded = $(this).attr('aria-expanded') === 'true';
            
            if (!isExpanded) {
                // Opening the menu
                previousFocus = document.activeElement;
                $(this).attr('aria-expanded', 'true');
                
                // Focus the first menu item after animation completes
                setTimeout(function() {
                    $mobileMenu.find('a').first().focus();
                }, 300);
            } else {
                // Closing the menu
                $(this).attr('aria-expanded', 'false');
                
                // Restore focus
                setTimeout(function() {
                    if (previousFocus) {
                        previousFocus.focus();
                    }
                }, 300);
            }
        });
    }

    // Setup dropdown focus management
    function setupDropdownFocus() {
        // Handle dropdown toggle click
        $dropdownToggles.on('click', function() {
            var $this = $(this);
            var isExpanded = $this.attr('aria-expanded') === 'true';
            
            // Close all other dropdowns
            $dropdownToggles.not($this).attr('aria-expanded', 'false');
            $('.dropdown-menu').removeClass('show');
            
            if (!isExpanded) {
                // Opening the dropdown
                $this.attr('aria-expanded', 'true');
                var $submenu = $this.parent().find('.dropdown-menu');
                $submenu.addClass('show');
                
                // Focus the first menu item
                setTimeout(function() {
                    $submenu.find('a').first().focus();
                }, 100);
            } else {
                // Closing the dropdown
                $this.attr('aria-expanded', 'false');
                $this.parent().find('.dropdown-menu').removeClass('show');
            }
        });

        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.menu-item-has-children').length) {
                $dropdownToggles.attr('aria-expanded', 'false');
                $('.dropdown-menu').removeClass('show');
            }
        });
    }

    // Setup quick view modal focus management
    function setupQuickViewModalFocus() {
        // Store previous focus when quick view modal is opened
        $(document).on('click', '.quick-view-button', function() {
            previousFocus = document.activeElement;
        });

        // Focus the close button when quick view modal is opened
        $(document).on('wc_quick_view_loaded', function() {
            setTimeout(function() {
                $('.quick-view-close').focus();
            }, 100);
        });

        // Restore focus when quick view modal is closed
        $(document).on('click', '.quick-view-close', function() {
            setTimeout(function() {
                if (previousFocus) {
                    previousFocus.focus();
                }
            }, 100);
        });
    }

    // Initialize when DOM is ready
    $(document).ready(init);

})(jQuery);