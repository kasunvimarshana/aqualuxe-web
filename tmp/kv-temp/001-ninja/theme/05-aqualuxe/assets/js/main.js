/**
 * AquaLuxe Theme Main JavaScript
 *
 * This file contains the main JavaScript functionality for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Theme Object
     */
    var AquaLuxe = {
        /**
         * Initialize
         */
        init: function() {
            // Initialize components
            this.navigationMenu();
            this.mobileMenu();
            this.stickyHeader();
            this.backToTop();
            this.searchToggle();
            this.accessibilityFocus();
            this.skipLinkFocusFix();
        },

        /**
         * Navigation Menu
         */
        navigationMenu: function() {
            // Add dropdown toggle button
            $('.main-navigation .menu-item-has-children > a').after('<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">Expand child menu</span></button>');

            // Toggle submenu on click
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                
                var $this = $(this);
                var $parent = $this.parent();
                var $subMenu = $parent.children('.sub-menu');
                
                $this.toggleClass('toggled');
                $subMenu.toggleClass('toggled');
                
                // Update aria-expanded attribute
                $this.attr('aria-expanded', $this.hasClass('toggled'));
            });

            // Close submenus when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.menu-item-has-children').length) {
                    $('.dropdown-toggle.toggled').removeClass('toggled').attr('aria-expanded', 'false');
                    $('.sub-menu.toggled').removeClass('toggled');
                }
            });

            // Add current-menu-ancestor class to parent menu items
            $('.current-menu-item, .current-menu-ancestor').parents('.menu-item-has-children').addClass('current-menu-ancestor');
        },

        /**
         * Mobile Menu
         */
        mobileMenu: function() {
            // Toggle mobile menu
            $('.menu-toggle').on('click', function(e) {
                e.preventDefault();
                
                var $this = $(this);
                var $siteHeader = $('.site-header');
                var $mainNav = $('.main-navigation');
                
                $this.toggleClass('toggled');
                $siteHeader.toggleClass('mobile-menu-active');
                $mainNav.toggleClass('toggled');
                
                // Update aria-expanded attribute
                $this.attr('aria-expanded', $this.hasClass('toggled'));
            });

            // Close mobile menu on window resize
            $(window).on('resize', function() {
                if (window.innerWidth > 991) {
                    $('.menu-toggle').removeClass('toggled').attr('aria-expanded', 'false');
                    $('.site-header').removeClass('mobile-menu-active');
                    $('.main-navigation').removeClass('toggled');
                    $('.dropdown-toggle.toggled').removeClass('toggled').attr('aria-expanded', 'false');
                    $('.sub-menu.toggled').removeClass('toggled');
                }
            });
        },

        /**
         * Sticky Header
         */
        stickyHeader: function() {
            var $window = $(window);
            var $siteHeader = $('.site-header');
            var headerHeight = $siteHeader.outerHeight();
            var scrollTop = $window.scrollTop();
            var lastScrollTop = 0;
            var scrollDelta = 10;
            var stickyClass = 'sticky-header';
            var hiddenClass = 'header-hidden';

            // Check if sticky header is enabled
            if (!$siteHeader.hasClass('sticky-enabled')) {
                return;
            }

            // Add placeholder to prevent content jump
            $siteHeader.after('<div class="header-placeholder" style="height: ' + headerHeight + 'px;"></div>');

            // Handle scroll event
            $window.on('scroll', function() {
                scrollTop = $window.scrollTop();

                // Check if scrolled more than header height
                if (scrollTop > headerHeight) {
                    $siteHeader.addClass(stickyClass);

                    // Hide header when scrolling down, show when scrolling up
                    if (Math.abs(lastScrollTop - scrollTop) > scrollDelta) {
                        if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
                            $siteHeader.addClass(hiddenClass);
                        } else {
                            $siteHeader.removeClass(hiddenClass);
                        }
                        lastScrollTop = scrollTop;
                    }
                } else {
                    $siteHeader.removeClass(stickyClass + ' ' + hiddenClass);
                }
            });

            // Update header placeholder height on window resize
            $window.on('resize', function() {
                headerHeight = $siteHeader.outerHeight();
                $('.header-placeholder').css('height', headerHeight + 'px');
            });
        },

        /**
         * Back to Top Button
         */
        backToTop: function() {
            var $backToTop = $('.back-to-top');
            
            // Show/hide back to top button based on scroll position
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    $backToTop.addClass('show');
                } else {
                    $backToTop.removeClass('show');
                }
            });
            
            // Smooth scroll to top when clicking the button
            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        },

        /**
         * Search Toggle
         */
        searchToggle: function() {
            $('.search-toggle').on('click', function(e) {
                e.preventDefault();
                
                var $this = $(this);
                var $headerSearch = $this.closest('.header-search');
                
                $headerSearch.toggleClass('active');
                
                // Focus search field when opened
                if ($headerSearch.hasClass('active')) {
                    $headerSearch.find('.search-field').focus();
                }
            });
            
            // Close search when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.header-search').length) {
                    $('.header-search.active').removeClass('active');
                }
            });
        },

        /**
         * Accessibility Focus
         */
        accessibilityFocus: function() {
            // Add focus class to menu items
            $('.main-navigation a, .secondary-navigation a, .footer-navigation a').on('focus blur', function() {
                $(this).parents('li').toggleClass('focus');
            });
        },

        /**
         * Skip Link Focus Fix
         * 
         * Helps with accessibility for keyboard only users.
         * Learn more: https://git.io/vWdr2
         */
        skipLinkFocusFix: function() {
            var isIe = /(trident|msie)/i.test(navigator.userAgent);

            if (isIe && document.getElementById && window.addEventListener) {
                window.addEventListener('hashchange', function() {
                    var id = location.hash.substring(1);

                    if (!(/^[A-z0-9_-]+$/.test(id))) {
                        return;
                    }

                    var element = document.getElementById(id);

                    if (element) {
                        if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                            element.tabIndex = -1;
                        }

                        element.focus();
                    }
                }, false);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxe.init();
    });

})(jQuery);