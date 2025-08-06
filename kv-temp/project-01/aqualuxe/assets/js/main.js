/**
 * AquaLuxe Theme Main JavaScript
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Main theme object
    const AquaLuxe = {

        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initMobileMenu();
            this.initStickyHeader();
            this.initScrollAnimations();
            this.initProductQuickView();
            this.initSearchEnhancement();
            this.initAccessibility();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).ready(this.init.bind(this));
            $(window).on('scroll', this.handleScroll.bind(this));
            $(window).on('resize', this.handleResize.bind(this));
        },

        /**
         * Initialize mobile menu
         */
        initMobileMenu: function() {
            // Create mobile menu toggle
            if (!$('.mobile-menu-toggle').length) {
                $('.site-header').append('<button class="mobile-menu-toggle" aria-label="Toggle Menu"><span></span><span></span><span></span></button>');
            }

            // Mobile menu toggle functionality
            $('.mobile-menu-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('active');
                $('.main-navigation').toggleClass('mobile-active');
                $('body').toggleClass('mobile-menu-open');
            });

            // Close menu on escape
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && $('body').hasClass('mobile-menu-open')) {
                    $('.mobile-menu-toggle').removeClass('active');
                    $('.main-navigation').removeClass('mobile-active');
                    $('body').removeClass('mobile-menu-open');
                }
            });

            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.main-navigation, .mobile-menu-toggle').length) {
                    $('.mobile-menu-toggle').removeClass('active');
                    $('.main-navigation').removeClass('mobile-active');
                    $('body').removeClass('mobile-menu-open');
                }
            });
        },

        /**
         * Initialize sticky header
         */
        initStickyHeader: function() {
            const header = $('.site-header');
            let lastScrollTop = 0;

            if (header.length) {
                header.addClass('sticky-header');
            }
        },

        /**
         * Handle scroll events
         */
        handleScroll: function() {
            const scrollTop = $(window).scrollTop();
            const header = $('.site-header');

            // Sticky header effect
            if (scrollTop > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }

            // Scroll to top button
            if (scrollTop > 500) {
                this.showScrollToTop();
            } else {
                this.hideScrollToTop();
            }

            // Animate elements on scroll
            this.animateOnScroll();
        },

        /**
         * Handle resize events
         */
        handleResize: function() {
            // Close mobile menu on larger screens
            if ($(window).width() > 768) {
                $('.mobile-menu-toggle').removeClass('active');
                $('.main-navigation').removeClass('mobile-active');
                $('body').removeClass('mobile-menu-open');
            }
        },

        /**
         * Initialize scroll animations
         */
        initScrollAnimations: function() {
            // Add animation classes to elements
            $('.woocommerce ul.products li.product').each(function(index) {
                $(this).attr('data-aos', 'fade-up');
                $(this).attr('data-aos-delay', index * 100);
            });
        },

        /**
         * Animate elements on scroll
         */
        animateOnScroll: function() {
            $('.animate-on-scroll').each(function() {
                const element = $(this);
                const elementTop = element.offset().top;
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();

                if (elementTop < viewportBottom - 100 && !element.hasClass('animated')) {
                    element.addClass('animated');
                }
            });
        },

        /**
         * Show scroll to top button
         */
        showScrollToTop: function() {
            if (!$('#scroll-to-top').length) {
                $('body').append('<button id="scroll-to-top" class="scroll-to-top" aria-label="Scroll to Top"><i class="fas fa-arrow-up"></i></button>');
                
                $('#scroll-to-top').on('click', function(e) {
                    e.preventDefault();
                    $('html, body').animate({ scrollTop: 0 }, 800);
                });
            }
            $('#scroll-to-top').fadeIn();
        },

        /**
         * Hide scroll to top button
         */
        hideScrollToTop: function() {
            $('#scroll-to-top').fadeOut();
        },

        /**
         * Initialize product quick view
         */
        initProductQuickView: function() {
            // Add quick view buttons to products
            $('.woocommerce ul.products li.product').each(function() {
                if (!$(this).find('.quick-view-button').length) {
                    const productId = $(this).find('[data-product_id]').attr('data-product_id');
                    if (productId) {
                        $(this).find('.woocommerce-loop-product__title').after(
                            '<button class="quick-view-button" data-product-id="' + productId + '">Quick View</button>'
                        );
                    }
                }
            });

            // Quick view functionality
            $(document).on('click', '.quick-view-button', function(e) {
                e.preventDefault();
                const productId = $(this).attr('data-product-id');
                AquaLuxe.showQuickView(productId);
            });

            // Close quick view modal
            $(document).on('click', '.quick-view-overlay, .quick-view-close', function(e) {
                if (e.target === this || $(e.target).hasClass('quick-view-close')) {
                    AquaLuxe.hideQuickView();
                }
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) {
                    AquaLuxe.hideQuickView();
                }
            });
        },

        /**
         * Show product quick view
         */
        showQuickView: function(productId) {
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    nonce: aqualuxe_ajax.nonce
                },
                beforeSend: function() {
                    AquaLuxe.showLoader();
                },
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        const modal = `
                            <div class="quick-view-overlay">
                                <div class="quick-view-modal">
                                    <button class="quick-view-close" aria-label="Close">&times;</button>
                                    <div class="quick-view-content">
                                        <div class="quick-view-image">
                                            <img src="${data.image}" alt="${data.title}">
                                        </div>
                                        <div class="quick-view-details">
                                            <h3>${data.title}</h3>
                                            <div class="price">${data.price}</div>
                                            <div class="description">${data.description}</div>
                                            <a href="${data.add_to_cart_url}" class="button add_to_cart_button">Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('body').append(modal);
                        $('body').addClass('quick-view-open');
                    }
                },
                complete: function() {
                    AquaLuxe.hideLoader();
                },
                error: function() {
                    alert('Error loading product details');
                }
            });
        },

        /**
         * Hide product quick view
         */
        hideQuickView: function() {
            $('.quick-view-overlay').fadeOut(300, function() {
                $(this).remove();
                $('body').removeClass('quick-view-open');
            });
        },

        /**
         * Show loader
         */
        showLoader: function() {
            if (!$('.aqualuxe-loader').length) {
                $('body').append('<div class="aqualuxe-loader"><div class="spinner"></div></div>');
            }
            $('.aqualuxe-loader').fadeIn();
        },

        /**
         * Hide loader
         */
        hideLoader: function() {
            $('.aqualuxe-loader').fadeOut();
        },

        /**
         * Initialize search enhancement
         */
        initSearchEnhancement: function() {
            const searchForm = $('.search-form');
            const searchField = searchForm.find('input[type="search"]');

            // Add search suggestions
            searchField.on('input', function() {
                const query = $(this).val();
                if (query.length > 2) {
                    AquaLuxe.showSearchSuggestions(query);
                } else {
                    AquaLuxe.hideSearchSuggestions();
                }
            });

            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-form').length) {
                    AquaLuxe.hideSearchSuggestions();
                }
            });
        },

        /**
         * Show search suggestions
         */
        showSearchSuggestions: function(query) {
            // Implementation for search suggestions
            // This would typically make an AJAX call to get suggestions
        },

        /**
         * Hide search suggestions
         */
        hideSearchSuggestions: function() {
            $('.search-suggestions').fadeOut();
        },

        /**
         * Initialize accessibility features
         */
        initAccessibility: function() {
            // Add focus indicators
            $('a, button, input, textarea, select').on('focus', function() {
                $(this).addClass('focused');
            }).on('blur', function() {
                $(this).removeClass('focused');
            });

            // Skip to content link
            if (!$('#skip-to-content').length) {
                $('body').prepend('<a href="#main" id="skip-to-content" class="screen-reader-text">Skip to content</a>');
            }

            // Improve form labels
            $('input[type="email"], input[type="password"], input[type="text"], textarea').each(function() {
                const input = $(this);
                if (!input.attr('aria-label') && !input.prev('label').length) {
                    const placeholder = input.attr('placeholder');
                    if (placeholder) {
                        input.attr('aria-label', placeholder);
                    }
                }
            });
        },

        /**
         * Utility functions
         */
        utils: {
            /**
             * Debounce function
             */
            debounce: function(func, wait, immediate) {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    const later = function() {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    };
                    const callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) func.apply(context, args);
                };
            },

            /**
             * Throttle function
             */
            throttle: function(func, limit) {
                let inThrottle;
                return function() {
                    const args = arguments;
                    const context = this;
                    if (!inThrottle) {
                        func.apply(context, args);
                        inThrottle = true;
                        setTimeout(() => inThrottle = false, limit);
                    }
                };
            }
        }
    };

    // Initialize theme
    AquaLuxe.init();

    // Make AquaLuxe globally available
    window.AquaLuxe = AquaLuxe;

})(jQuery);