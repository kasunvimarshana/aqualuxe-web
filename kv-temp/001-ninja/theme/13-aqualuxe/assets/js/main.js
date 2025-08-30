/**
 * AquaLuxe Theme Main JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Main Object
     */
    var AquaLuxe = {
        /**
         * Initialize
         */
        init: function() {
            this.mobileMenu();
            this.searchToggle();
            this.stickyHeader();
            this.languageSwitcher();
            this.initTooltips();
            this.initDropdowns();
            this.initTabs();
            this.initAccordions();
            this.initModals();
            this.initScrollToTop();
            this.initQuantityButtons();
            this.initAOS();
        },

        /**
         * Mobile Menu
         */
        mobileMenu: function() {
            var $menuToggle = $('#mobile-menu-toggle');
            var $mobileMenu = $('#mobile-menu');

            $menuToggle.on('click', function() {
                $mobileMenu.slideToggle(300);
                $(this).toggleClass('active');
            });

            // Close menu on window resize
            $(window).on('resize', function() {
                if ($(window).width() > 1024 && $mobileMenu.is(':visible')) {
                    $mobileMenu.hide();
                    $menuToggle.removeClass('active');
                }
            });

            // Handle submenu toggles
            $('.mobile-menu-items .menu-item-has-children > a').after('<span class="submenu-toggle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></span>');
            
            $('.submenu-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('active');
                $(this).next('.sub-menu').slideToggle(300);
            });
        },

        /**
         * Search Toggle
         */
        searchToggle: function() {
            var $searchToggle = $('#search-toggle');
            var $searchOverlay = $('#search-overlay');
            var $searchClose = $('#search-close');

            $searchToggle.on('click', function(e) {
                e.preventDefault();
                $searchOverlay.fadeIn(300);
                $searchOverlay.find('input[type="search"]').focus();
            });

            $searchClose.on('click', function(e) {
                e.preventDefault();
                $searchOverlay.fadeOut(300);
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) {
                    $searchOverlay.fadeOut(300);
                }
            });

            // Close on overlay click
            $searchOverlay.on('click', function(e) {
                if ($(e.target).is($searchOverlay)) {
                    $searchOverlay.fadeOut(300);
                }
            });
        },

        /**
         * Sticky Header
         */
        stickyHeader: function() {
            var $header = $('#masthead');
            var headerHeight = $header.outerHeight();
            var scrollPosition = $(window).scrollTop();
            var isSticky = false;

            // Check if sticky header is enabled
            if (!$header.hasClass('sticky')) {
                return;
            }

            // Initial check
            if (scrollPosition > headerHeight) {
                $header.addClass('is-sticky');
                $('body').css('padding-top', headerHeight);
                isSticky = true;
            }

            // On scroll
            $(window).on('scroll', function() {
                scrollPosition = $(window).scrollTop();

                if (scrollPosition > headerHeight && !isSticky) {
                    $header.addClass('is-sticky');
                    $('body').css('padding-top', headerHeight);
                    isSticky = true;
                } else if (scrollPosition <= headerHeight && isSticky) {
                    $header.removeClass('is-sticky');
                    $('body').css('padding-top', 0);
                    isSticky = false;
                }
            });
        },

        /**
         * Language Switcher
         */
        languageSwitcher: function() {
            var $languageSwitcher = $('.language-switcher');
            var $languageDropdown = $('.language-dropdown');

            $languageSwitcher.find('button').on('click', function(e) {
                e.preventDefault();
                $languageDropdown.toggleClass('hidden');
            });

            // Close on click outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.language-switcher').length) {
                    $languageDropdown.addClass('hidden');
                }
            });
        },

        /**
         * Initialize Tooltips
         */
        initTooltips: function() {
            $('.tooltip').each(function() {
                var $this = $(this);
                var $tooltip = $this.find('.tooltip-content');

                $this.on('mouseenter', function() {
                    $tooltip.removeClass('hidden');
                }).on('mouseleave', function() {
                    $tooltip.addClass('hidden');
                });
            });
        },

        /**
         * Initialize Dropdowns
         */
        initDropdowns: function() {
            $('.dropdown').each(function() {
                var $this = $(this);
                var $toggle = $this.find('.dropdown-toggle');
                var $content = $this.find('.dropdown-content');

                $toggle.on('click', function(e) {
                    e.preventDefault();
                    $content.toggleClass('hidden');
                });

                // Close on click outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.dropdown').length) {
                        $content.addClass('hidden');
                    }
                });
            });
        },

        /**
         * Initialize Tabs
         */
        initTabs: function() {
            $('.tabs').each(function() {
                var $tabs = $(this);
                var $tabButtons = $tabs.find('.tab-button');
                var $tabContents = $tabs.find('.tab-content');

                $tabButtons.on('click', function(e) {
                    e.preventDefault();
                    var target = $(this).data('tab');

                    // Update active tab button
                    $tabButtons.removeClass('active');
                    $(this).addClass('active');

                    // Show target tab content
                    $tabContents.addClass('hidden');
                    $tabs.find('.tab-content[data-tab="' + target + '"]').removeClass('hidden');
                });
            });
        },

        /**
         * Initialize Accordions
         */
        initAccordions: function() {
            $('.accordion').each(function() {
                var $accordion = $(this);
                var $items = $accordion.find('.accordion-item');
                var $headers = $accordion.find('.accordion-header');
                var $contents = $accordion.find('.accordion-content');

                $headers.on('click', function() {
                    var $header = $(this);
                    var $item = $header.parent();
                    var $content = $header.next('.accordion-content');
                    var isOpen = $item.hasClass('open');

                    // Close all items if accordion is not multiple
                    if (!$accordion.hasClass('multiple') && !isOpen) {
                        $items.removeClass('open');
                        $contents.slideUp(300);
                    }

                    // Toggle clicked item
                    $item.toggleClass('open', !isOpen);
                    $content.slideToggle(300);
                });
            });
        },

        /**
         * Initialize Modals
         */
        initModals: function() {
            $('.modal-trigger').on('click', function(e) {
                e.preventDefault();
                var target = $(this).data('modal');
                var $modal = $('#' + target);

                $modal.removeClass('hidden');
            });

            $('.modal-close').on('click', function() {
                $(this).closest('.modal').addClass('hidden');
            });

            // Close on overlay click
            $('.modal').on('click', function(e) {
                if ($(e.target).is('.modal')) {
                    $(this).addClass('hidden');
                }
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) {
                    $('.modal').addClass('hidden');
                }
            });
        },

        /**
         * Initialize Scroll to Top
         */
        initScrollToTop: function() {
            var $scrollTop = $('#scroll-top');

            if (!$scrollTop.length) {
                return;
            }

            // Show/hide button based on scroll position
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    $scrollTop.fadeIn(300);
                } else {
                    $scrollTop.fadeOut(300);
                }
            });

            // Scroll to top on click
            $scrollTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 500);
            });
        },

        /**
         * Initialize Quantity Buttons
         */
        initQuantityButtons: function() {
            // Quantity buttons for WooCommerce
            $(document).on('click', '.quantity-button', function() {
                var $button = $(this);
                var $input = $button.parent().find('input.qty');
                var oldValue = parseFloat($input.val());
                var max = parseFloat($input.attr('max'));
                var min = parseFloat($input.attr('min'));
                var step = parseFloat($input.attr('step'));

                if (isNaN(oldValue)) {
                    oldValue = 0;
                }

                if (isNaN(max)) {
                    max = 100;
                }

                if (isNaN(min)) {
                    min = 0;
                }

                if (isNaN(step)) {
                    step = 1;
                }

                if ($button.hasClass('plus')) {
                    if (oldValue < max) {
                        var newVal = oldValue + step;
                    } else {
                        var newVal = max;
                    }
                } else {
                    if (oldValue > min) {
                        var newVal = oldValue - step;
                    } else {
                        var newVal = min;
                    }
                }

                $input.val(newVal);
                $input.trigger('change');
            });
        },

        /**
         * Initialize AOS (Animate on Scroll)
         */
        initAOS: function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    offset: 100
                });
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxe.init();
    });

})(jQuery);