/**
 * AquaLuxe Theme Main JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Global variables
    var $window = $(window),
        $document = $(document),
        $body = $('body'),
        $header = $('.site-header'),
        $mainNav = $('.main-navigation'),
        $mobileMenu = $('.menu-toggle');

    // AquaLuxe object
    var AquaLuxe = {
        /**
         * Initialize
         */
        init: function() {
            // Document ready
            $(function() {
                AquaLuxe.stickyHeader();
                AquaLuxe.mobileMenu();
                AquaLuxe.searchToggle();
                AquaLuxe.backToTop();
                AquaLuxe.initSliders();
                AquaLuxe.productQuickView();
                AquaLuxe.ajaxAddToCart();
                AquaLuxe.productGallery();
                AquaLuxe.quantityButtons();
                AquaLuxe.wishlistToggle();
                AquaLuxe.compareToggle();
                AquaLuxe.productFilters();
                AquaLuxe.initLightbox();
                AquaLuxe.animateOnScroll();
                AquaLuxe.contactForm();
                AquaLuxe.newsletterForm();
            });

            // Window load
            $window.on('load', function() {
                AquaLuxe.preloader();
            });

            // Window scroll
            $window.on('scroll', function() {
                AquaLuxe.stickyHeaderOnScroll();
                AquaLuxe.backToTopOnScroll();
            });

            // Window resize
            $window.on('resize', function() {
                AquaLuxe.mobileMenuOnResize();
            });
        },

        /**
         * Preloader
         */
        preloader: function() {
            $('.preloader').fadeOut(500);
        },

        /**
         * Sticky Header
         */
        stickyHeader: function() {
            if ($header.hasClass('sticky-header')) {
                var headerHeight = $header.outerHeight();
                $body.css('padding-top', headerHeight);
            }
        },

        /**
         * Sticky Header on Scroll
         */
        stickyHeaderOnScroll: function() {
            if ($header.hasClass('sticky-header')) {
                var scrollTop = $window.scrollTop();
                
                if (scrollTop > 200) {
                    $header.addClass('is-sticky');
                } else {
                    $header.removeClass('is-sticky');
                }
            }
        },

        /**
         * Mobile Menu
         */
        mobileMenu: function() {
            // Toggle menu
            $mobileMenu.on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('toggled');
                $mainNav.find('.primary-menu').toggleClass('toggled');
            });

            // Toggle submenu
            $mainNav.find('.menu-item-has-children > a').on('click', function(e) {
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    $(this).parent().toggleClass('toggled');
                    $(this).parent().find('> .sub-menu').slideToggle(300);
                }
            });
        },

        /**
         * Mobile Menu on Resize
         */
        mobileMenuOnResize: function() {
            if (window.innerWidth >= 992) {
                $mainNav.find('.primary-menu').removeClass('toggled');
                $mainNav.find('.menu-item-has-children').removeClass('toggled');
                $mainNav.find('.sub-menu').removeAttr('style');
                $mobileMenu.removeClass('toggled');
            }
        },

        /**
         * Search Toggle
         */
        searchToggle: function() {
            $('.search-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('active');
                $('.search-form-wrapper').toggleClass('active');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.header-search').length) {
                    $('.search-toggle').removeClass('active');
                    $('.search-form-wrapper').removeClass('active');
                }
            });
        },

        /**
         * Back to Top
         */
        backToTop: function() {
            $('#back-to-top').on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
            });
        },

        /**
         * Back to Top on Scroll
         */
        backToTopOnScroll: function() {
            var scrollTop = $window.scrollTop();
            
            if (scrollTop > 600) {
                $('#back-to-top').addClass('show');
            } else {
                $('#back-to-top').removeClass('show');
            }
        },

        /**
         * Initialize Sliders
         */
        initSliders: function() {
            // Hero slider
            if ($.fn.slick && $('.hero-slider').length) {
                $('.hero-slider').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    dots: true,
                    arrows: true,
                    fade: true,
                    cssEase: 'cubic-bezier(0.7, 0, 0.3, 1)',
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                arrows: false
                            }
                        }
                    ]
                });
            }

            // Testimonials slider
            if ($.fn.slick && $('.testimonials-slider').length) {
                $('.testimonials-slider').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    dots: true,
                    arrows: false,
                    responsive: [
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            }

            // Species slider
            if ($.fn.slick && $('.species-slider').length) {
                $('.species-slider').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    dots: false,
                    arrows: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                arrows: false,
                                dots: true
                            }
                        }
                    ]
                });
            }

            // Team slider
            if ($.fn.slick && $('.team-slider').length) {
                $('.team-slider').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    dots: false,
                    arrows: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                arrows: false,
                                dots: true
                            }
                        }
                    ]
                });
            }

            // Product slider
            if ($.fn.slick && $('.product-slider').length) {
                $('.product-slider').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    autoplay: false,
                    dots: false,
                    arrows: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                arrows: false,
                                dots: true
                            }
                        }
                    ]
                });
            }
        },

        /**
         * Product Quick View
         */
        productQuickView: function() {
            $('.quick-view-button').on('click', function(e) {
                e.preventDefault();
                
                var productId = $(this).data('product-id');
                
                // Show loading
                $body.append('<div class="quick-view-loading"><div class="spinner"></div></div>');
                
                // AJAX request
                $.ajax({
                    url: aqualuxe_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxe_params.nonce
                    },
                    success: function(response) {
                        // Remove loading
                        $('.quick-view-loading').remove();
                        
                        // Append modal
                        $body.append(response);
                        
                        // Initialize product gallery
                        AquaLuxe.productGallery();
                        
                        // Initialize quantity buttons
                        AquaLuxe.quantityButtons();
                        
                        // Open modal
                        $('.quick-view-modal').fadeIn(300);
                        
                        // Close modal
                        $('.quick-view-close, .quick-view-overlay').on('click', function() {
                            $('.quick-view-modal').fadeOut(300, function() {
                                $(this).remove();
                            });
                        });
                    }
                });
            });
        },

        /**
         * AJAX Add to Cart
         */
        ajaxAddToCart: function() {
            $(document).on('click', '.ajax_add_to_cart', function(e) {
                $(this).addClass('loading');
            });
            
            $body.on('added_to_cart', function(event, fragments, cart_hash, $button) {
                $button.removeClass('loading');
                
                // Show mini cart
                $('.header-cart .widget_shopping_cart').addClass('show');
                
                // Hide mini cart after 5 seconds
                setTimeout(function() {
                    $('.header-cart .widget_shopping_cart').removeClass('show');
                }, 5000);
            });
        },

        /**
         * Product Gallery
         */
        productGallery: function() {
            if ($.fn.slick) {
                // Product gallery slider
                $('.product-gallery-slider').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: '.product-gallery-nav'
                });
                
                // Product gallery navigation
                $('.product-gallery-nav').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: '.product-gallery-slider',
                    dots: false,
                    arrows: false,
                    focusOnSelect: true,
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3
                            }
                        }
                    ]
                });
            }
        },

        /**
         * Quantity Buttons
         */
        quantityButtons: function() {
            $(document).on('click', '.quantity-button', function() {
                var $button = $(this);
                var $input = $button.parent().find('input');
                var oldValue = parseFloat($input.val());
                var newVal;
                
                if ($button.hasClass('plus')) {
                    newVal = oldValue + 1;
                } else {
                    if (oldValue > 1) {
                        newVal = oldValue - 1;
                    } else {
                        newVal = 1;
                    }
                }
                
                $input.val(newVal);
                $input.trigger('change');
            });
        },

        /**
         * Wishlist Toggle
         */
        wishlistToggle: function() {
            $('.add-to-wishlist').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var productId = $button.data('product-id');
                
                $button.addClass('loading');
                
                // AJAX request
                $.ajax({
                    url: aqualuxe_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_toggle_wishlist',
                        product_id: productId,
                        nonce: aqualuxe_params.nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            if (response.data.status === 'added') {
                                $button.addClass('added');
                            } else {
                                $button.removeClass('added');
                            }
                            
                            // Update wishlist count
                            $('.wishlist-count').text(response.data.count);
                        }
                    }
                });
            });
        },

        /**
         * Compare Toggle
         */
        compareToggle: function() {
            $('.add-to-compare').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var productId = $button.data('product-id');
                
                $button.addClass('loading');
                
                // AJAX request
                $.ajax({
                    url: aqualuxe_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_toggle_compare',
                        product_id: productId,
                        nonce: aqualuxe_params.nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            if (response.data.status === 'added') {
                                $button.addClass('added');
                            } else {
                                $button.removeClass('added');
                            }
                            
                            // Update compare count
                            $('.compare-count').text(response.data.count);
                        }
                    }
                });
            });
        },

        /**
         * Product Filters
         */
        productFilters: function() {
            // Filter toggle
            $('.filter-toggle').on('click', function(e) {
                e.preventDefault();
                $('.product-filters').slideToggle(300);
            });
            
            // AJAX filter
            $('.product-filter-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var formData = $form.serialize();
                
                // Show loading
                $('.products-wrapper').addClass('loading');
                
                // AJAX request
                $.ajax({
                    url: aqualuxe_params.ajax_url,
                    type: 'POST',
                    data: formData + '&action=aqualuxe_filter_products&nonce=' + aqualuxe_params.nonce,
                    success: function(response) {
                        // Remove loading
                        $('.products-wrapper').removeClass('loading');
                        
                        // Update products
                        $('.products-wrapper').html(response);
                        
                        // Update URL
                        history.pushState(null, '', '?' + formData);
                    }
                });
            });
        },

        /**
         * Initialize Lightbox
         */
        initLightbox: function() {
            if ($.fn.magnificPopup) {
                // Image lightbox
                $('.gallery-item a, .wp-block-gallery a, a.lightbox').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true
                    },
                    mainClass: 'mfp-fade',
                    removalDelay: 300
                });
                
                // Video lightbox
                $('.video-popup').magnificPopup({
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 300,
                    iframe: {
                        patterns: {
                            youtube: {
                                index: 'youtube.com/',
                                id: 'v=',
                                src: '//www.youtube.com/embed/%id%?autoplay=1'
                            },
                            vimeo: {
                                index: 'vimeo.com/',
                                id: '/',
                                src: '//player.vimeo.com/video/%id%?autoplay=1'
                            }
                        }
                    }
                });
            }
        },

        /**
         * Animate on Scroll
         */
        animateOnScroll: function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 120
                });
            }
        },

        /**
         * Contact Form
         */
        contactForm: function() {
            $('.contact-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var formData = $form.serialize();
                
                // Disable submit button
                $form.find('button[type="submit"]').prop('disabled', true).addClass('loading');
                
                // AJAX request
                $.ajax({
                    url: aqualuxe_params.ajax_url,
                    type: 'POST',
                    data: formData + '&action=aqualuxe_contact_form&nonce=' + aqualuxe_params.nonce,
                    success: function(response) {
                        // Enable submit button
                        $form.find('button[type="submit"]').prop('disabled', false).removeClass('loading');
                        
                        if (response.success) {
                            // Show success message
                            $form.find('.form-message').html('<div class="alert alert-success">' + response.data.message + '</div>');
                            
                            // Reset form
                            $form[0].reset();
                        } else {
                            // Show error message
                            $form.find('.form-message').html('<div class="alert alert-danger">' + response.data.message + '</div>');
                        }
                    }
                });
            });
        },

        /**
         * Newsletter Form
         */
        newsletterForm: function() {
            $('.newsletter-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var formData = $form.serialize();
                
                // Disable submit button
                $form.find('button[type="submit"]').prop('disabled', true).addClass('loading');
                
                // AJAX request
                $.ajax({
                    url: aqualuxe_params.ajax_url,
                    type: 'POST',
                    data: formData + '&action=aqualuxe_newsletter_form&nonce=' + aqualuxe_params.nonce,
                    success: function(response) {
                        // Enable submit button
                        $form.find('button[type="submit"]').prop('disabled', false).removeClass('loading');
                        
                        if (response.success) {
                            // Show success message
                            $form.find('.form-message').html('<div class="alert alert-success">' + response.data.message + '</div>');
                            
                            // Reset form
                            $form[0].reset();
                        } else {
                            // Show error message
                            $form.find('.form-message').html('<div class="alert alert-danger">' + response.data.message + '</div>');
                        }
                    }
                });
            });
        }
    };

    // Initialize
    AquaLuxe.init();

})(jQuery);