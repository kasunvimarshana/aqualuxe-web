/**
 * Navigation Module
 * Handles all navigation-related functionality
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class Navigation {
        constructor() {
            this.init();
        }

        init() {
            this.handleMegaMenu();
            this.handleDropdowns();
            this.handleKeyboardNavigation();
        }

        /**
         * Handle mega menu functionality
         */
        handleMegaMenu() {
            $('.menu-item-has-children').on('mouseenter', function() {
                $(this).addClass('hover');
                $(this).find('.sub-menu').addClass('active');
            }).on('mouseleave', function() {
                $(this).removeClass('hover');
                $(this).find('.sub-menu').removeClass('active');
            });
        }

        /**
         * Handle dropdown menus
         */
        handleDropdowns() {
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                const $dropdown = $(this).next('.dropdown-menu');
                
                // Close other dropdowns
                $('.dropdown-menu').not($dropdown).removeClass('active');
                
                // Toggle current dropdown
                $dropdown.toggleClass('active');
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('active');
                }
            });
        }

        /**
         * Handle keyboard navigation
         */
        handleKeyboardNavigation() {
            $('.menu a').on('keydown', function(e) {
                const $currentItem = $(this).parent();
                let $targetItem;

                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        $targetItem = $currentItem.next();
                        if ($targetItem.length) {
                            $targetItem.find('a').first().focus();
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        $targetItem = $currentItem.prev();
                        if ($targetItem.length) {
                            $targetItem.find('a').first().focus();
                        }
                        break;

                    case 'ArrowRight':
                        e.preventDefault();
                        const $submenu = $currentItem.find('.sub-menu').first();
                        if ($submenu.length) {
                            $submenu.addClass('active');
                            $submenu.find('a').first().focus();
                        }
                        break;

                    case 'ArrowLeft':
                        e.preventDefault();
                        const $parentMenu = $currentItem.closest('.sub-menu');
                        if ($parentMenu.length) {
                            $parentMenu.removeClass('active');
                            $parentMenu.siblings('a').focus();
                        }
                        break;

                    case 'Escape':
                        e.preventDefault();
                        $('.sub-menu').removeClass('active');
                        $(this).blur();
                        break;
                }
            });
        }
    }

    // Initialize navigation
    new Navigation();

})(jQuery);