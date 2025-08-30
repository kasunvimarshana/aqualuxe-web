/**
 * Main JavaScript for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Theme object
    var AquaLuxe = {
        
        /**
         * Initialize all functions
         */
        init: function() {
            this.stickyHeader();
            this.mobileNavigation();
            this.searchToggle();
            this.backToTop();
            this.lazyLoading();
            this.smoothScrolling();
            this.formValidation();
            this.animations();
            this.accessibility();
        },

        /**
         * Sticky header functionality
         */
        stickyHeader: function() {
            var $header = $('.site-header');
            var headerOffset = $header.offset().top;
            var $window = $(window);

            function updateHeader() {
                if ($window.scrollTop() > headerOffset) {
                    $header.addClass('scrolled');
                } else {
                    $header.removeClass('scrolled');
                }
            }

            $window.on('scroll', updateHeader);
            updateHeader(); // Initial check
        },

        /**
         * Mobile navigation
         */
        mobileNavigation: function() {
            var $menuToggle = $('.menu-toggle');
            var $navigation = $('.main-navigation');
            var $body = $('body');

            $menuToggle.on('click', function(e) {
                e.preventDefault();
                
                var isExpanded = $(this).attr('aria-expanded') === 'true';
                
                $(this).attr('aria-expanded', !isExpanded);
                $navigation.toggleClass('toggled');
                $body.toggleClass('menu-open');
            });

            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.site-header').length) {
                    $menuToggle.attr('aria-expanded', 'false');
                    $navigation.removeClass('toggled');
                    $body.removeClass('menu-open');
                }
            });

            // Handle submenu toggles
            $('.main-navigation .menu-item-has-children > a').on('click', function(e) {
                if ($(window).width() < 768) {
                    e.preventDefault();
                    $(this).parent().toggleClass('open');
                    $(this).next('.sub-menu').slideToggle();
                }
            });
        },

        /**
         * Search toggle functionality
         */
        searchToggle: function() {
            var $searchToggle = $('.search-toggle');
            var $searchContainer = $('.search-form-container');

            $searchToggle.on('click', function(e) {
                e.preventDefault();
                $searchContainer.toggleClass('active');
                
                if ($searchContainer.hasClass('active')) {
                    $searchContainer.find('input[type="search"]').focus();
                }
            });

            // Close search when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.header-search').length) {
                    $searchContainer.removeClass('active');
                }
            });
        },

        /**
         * Back to top button
         */
        backToTop: function() {
            var $backToTop = $('#back-to-top');
            var $window = $(window);

            function toggleBackToTop() {
                if ($window.scrollTop() > 300) {
                    $backToTop.addClass('visible');
                } else {
                    $backToTop.removeClass('visible');
                }
            }

            $window.on('scroll', toggleBackToTop);
            toggleBackToTop(); // Initial check

            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
            });
        },

        /**
         * Lazy loading for images
         */
        lazyLoading: function() {
            if ('IntersectionObserver' in window) {
                var imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('loaded');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        },

        /**
         * Smooth scrolling for anchor links
         */
        smoothScrolling: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                    location.hostname === this.hostname) {
                    
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    
                    if (target.length) {
                        e.preventDefault();
                        var offset = target.offset().top - 80; // Account for fixed header
                        
                        $('html, body').animate({
                            scrollTop: offset
                        }, 600);
                    }
                }
            });
        },

        /**
         * Form validation enhancements
         */
        formValidation: function() {
            // Add validation classes
            $('form input, form textarea, form select').on('blur', function() {
                var $field = $(this);
                var value = $field.val();
                
                if ($field.prop('required') && !value) {
                    $field.addClass('error');
                } else {
                    $field.removeClass('error');
                }

                // Email validation
                if ($field.attr('type') === 'email' && value) {
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(value)) {
                        $field.removeClass('error');
                    } else {
                        $field.addClass('error');
                    }
                }
            });

            // Form submission handling
            $('form').on('submit', function(e) {
                var $form = $(this);
                var hasErrors = false;

                $form.find('input[required], textarea[required], select[required]').each(function() {
                    var $field = $(this);
                    if (!$field.val()) {
                        $field.addClass('error');
                        hasErrors = true;
                    }
                });

                if (hasErrors) {
                    e.preventDefault();
                    $form.find('.error').first().focus();
                }
            });
        },

        /**
         * Animation on scroll
         */
        animations: function() {
            if ('IntersectionObserver' in window) {
                var animationObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate');
                        }
                    });
                }, {
                    threshold: 0.1
                });

                // Observe elements with animation classes
                document.querySelectorAll('.fade-in-up, .fade-in, .slide-in-left, .slide-in-right').forEach(function(el) {
                    animationObserver.observe(el);
                });
            }
        },

        /**
         * Accessibility improvements
         */
        accessibility: function() {
            // Skip link functionality
            $('.skip-link').on('click', function(e) {
                var target = $($(this).attr('href'));
                if (target.length) {
                    target.attr('tabindex', '-1').focus();
                }
            });

            // Keyboard navigation for dropdowns
            $('.main-navigation a').on('keydown', function(e) {
                var $this = $(this);
                var $parent = $this.parent();
                var $submenu = $parent.find('.sub-menu').first();

                switch (e.keyCode) {
                    case 27: // Escape
                        if ($submenu.length) {
                            $submenu.removeClass('open');
                            $this.focus();
                        }
                        break;
                    case 40: // Down arrow
                        if ($submenu.length) {
                            e.preventDefault();
                            $submenu.addClass('open');
                            $submenu.find('a').first().focus();
                        }
                        break;
                    case 38: // Up arrow
                        if ($parent.parent().hasClass('sub-menu')) {
                            e.preventDefault();
                            $parent.parent().removeClass('open');
                            $parent.parent().siblings('a').focus();
                        }
                        break;
                }
            });

            // Focus management for modals and overlays
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) { // Escape key
                    $('.search-form-container.active').removeClass('active');
                    $('.mini-cart-container.active').removeClass('active');
                }
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxe.init();
    });

    // Handle window resize
    $(window).on('resize', function() {
        // Reset mobile menu on resize
        if ($(window).width() >= 768) {
            $('.main-navigation').removeClass('toggled');
            $('.menu-toggle').attr('aria-expanded', 'false');
            $('body').removeClass('menu-open');
        }
    });

})(jQuery);