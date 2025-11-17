/**
 * File keyboard-navigation.js.
 *
 * Handles keyboard navigation for accessibility.
 */
(function($) {
    'use strict';

    // Variables
    var tabFocusElements = 'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])';
    var $body = $('body');
    var $siteNavigation = $('#site-navigation');
    var $mobileMenuToggle = $('#mobile-menu-toggle');
    var $mobileMenu = $('#mobile-menu');
    var $searchToggle = $('#search-toggle');
    var $searchOverlay = $('#search-overlay');
    var $searchClose = $('#search-close');
    var $searchInput = $('.search-field');
    var $darkModeToggle = $('#dark-mode-toggle');
    var $backToTop = $('.back-to-top');
    var $dropdownToggles = $('.dropdown-toggle');

    // Initialize
    function init() {
        setupKeyboardNavigation();
        setupDropdownKeyboardNavigation();
        setupModalKeyboardNavigation();
        setupTabTrap();
    }

    // Setup keyboard navigation
    function setupKeyboardNavigation() {
        // Add focus styles
        $body.on('keydown', function(e) {
            if (e.key === 'Tab') {
                $body.addClass('keyboard-navigation');
            }
        });

        $body.on('mousedown', function() {
            $body.removeClass('keyboard-navigation');
        });

        // Handle Escape key
        $body.on('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close mobile menu if open
                if ($mobileMenu.is(':visible')) {
                    $mobileMenuToggle.trigger('click');
                    $mobileMenuToggle.focus();
                }

                // Close search overlay if open
                if ($searchOverlay.is(':visible')) {
                    $searchClose.trigger('click');
                    $searchToggle.focus();
                }

                // Close dropdowns if open
                $dropdownToggles.each(function() {
                    var $this = $(this);
                    if ($this.attr('aria-expanded') === 'true') {
                        $this.trigger('click');
                        $this.focus();
                    }
                });
            }
        });

        // Mobile menu toggle
        $mobileMenuToggle.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
            }
        });

        // Search toggle
        $searchToggle.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
                setTimeout(function() {
                    $searchInput.focus();
                }, 100);
            }
        });

        // Dark mode toggle
        $darkModeToggle.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
            }
        });

        // Back to top button
        $backToTop.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
                $('#page').focus();
            }
        });
    }

    // Setup dropdown keyboard navigation
    function setupDropdownKeyboardNavigation() {
        $dropdownToggles.on('keydown', function(e) {
            var $this = $(this);
            var $parent = $this.parent();
            var $submenu = $parent.find('.dropdown-menu');
            var $firstItem = $submenu.find('a').first();

            // Open dropdown on Enter or Space
            if ((e.key === 'Enter' || e.key === ' ') && $this.attr('aria-expanded') === 'false') {
                e.preventDefault();
                $this.attr('aria-expanded', 'true');
                $submenu.addClass('show');
                $firstItem.focus();
            }

            // Close dropdown on Enter or Space when open
            else if ((e.key === 'Enter' || e.key === ' ') && $this.attr('aria-expanded') === 'true') {
                e.preventDefault();
                $this.attr('aria-expanded', 'false');
                $submenu.removeClass('show');
            }

            // Open dropdown and focus first item on Arrow Down
            else if (e.key === 'ArrowDown' || e.key === 'Down') {
                e.preventDefault();
                $this.attr('aria-expanded', 'true');
                $submenu.addClass('show');
                $firstItem.focus();
            }

            // Close dropdown on Arrow Up
            else if (e.key === 'ArrowUp' || e.key === 'Up') {
                e.preventDefault();
                $this.attr('aria-expanded', 'false');
                $submenu.removeClass('show');
            }
        });

        // Handle keyboard navigation within dropdown menu
        $('.dropdown-menu a').on('keydown', function(e) {
            var $this = $(this);
            var $parent = $this.closest('.menu-item-has-children');
            var $toggle = $parent.find('.dropdown-toggle');
            var $menu = $this.closest('.dropdown-menu');
            var $items = $menu.find('a');
            var index = $items.index($this);
            var lastIndex = $items.length - 1;

            // Close dropdown and focus toggle on Escape
            if (e.key === 'Escape') {
                e.preventDefault();
                $toggle.attr('aria-expanded', 'false');
                $menu.removeClass('show');
                $toggle.focus();
            }

            // Focus previous item on Arrow Up
            else if (e.key === 'ArrowUp' || e.key === 'Up') {
                e.preventDefault();
                if (index > 0) {
                    $items.eq(index - 1).focus();
                } else {
                    $toggle.focus();
                }
            }

            // Focus next item on Arrow Down
            else if (e.key === 'ArrowDown' || e.key === 'Down') {
                e.preventDefault();
                if (index < lastIndex) {
                    $items.eq(index + 1).focus();
                }
            }

            // Close dropdown and focus toggle on Tab from last item
            else if (e.key === 'Tab' && !e.shiftKey && index === lastIndex) {
                $toggle.attr('aria-expanded', 'false');
                $menu.removeClass('show');
            }

            // Close dropdown and focus toggle on Shift+Tab from first item
            else if (e.key === 'Tab' && e.shiftKey && index === 0) {
                e.preventDefault();
                $toggle.attr('aria-expanded', 'false');
                $menu.removeClass('show');
                $toggle.focus();
            }
        });
    }

    // Setup modal keyboard navigation
    function setupModalKeyboardNavigation() {
        // Search overlay
        $searchClose.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
                $searchToggle.focus();
            }
        });

        // Quick view modal (if exists)
        $(document).on('keydown', '.quick-view-close', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
                // Focus the button that opened the modal
                $('.quick-view-button').focus();
            }
        });
    }

    // Setup tab trap for modals
    function setupTabTrap() {
        // Search overlay tab trap
        $searchOverlay.on('keydown', function(e) {
            if (e.key === 'Tab') {
                var $focusableElements = $searchOverlay.find(tabFocusElements);
                var $firstFocusable = $focusableElements.first();
                var $lastFocusable = $focusableElements.last();

                // If shift + tab on first element, focus last element
                if (e.shiftKey && document.activeElement === $firstFocusable[0]) {
                    e.preventDefault();
                    $lastFocusable.focus();
                }
                // If tab on last element, focus first element
                else if (!e.shiftKey && document.activeElement === $lastFocusable[0]) {
                    e.preventDefault();
                    $firstFocusable.focus();
                }
            }
        });

        // Quick view modal tab trap (if exists)
        $(document).on('keydown', '.quick-view-modal', function(e) {
            if (e.key === 'Tab') {
                var $modal = $(this);
                var $focusableElements = $modal.find(tabFocusElements);
                var $firstFocusable = $focusableElements.first();
                var $lastFocusable = $focusableElements.last();

                // If shift + tab on first element, focus last element
                if (e.shiftKey && document.activeElement === $firstFocusable[0]) {
                    e.preventDefault();
                    $lastFocusable.focus();
                }
                // If tab on last element, focus first element
                else if (!e.shiftKey && document.activeElement === $lastFocusable[0]) {
                    e.preventDefault();
                    $firstFocusable.focus();
                }
            }
        });
    }

    // Initialize when DOM is ready
    $(document).ready(init);

})(jQuery);