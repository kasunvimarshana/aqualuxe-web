/**
 * Main JavaScript file for the AquaLuxe theme
 *
 * Contains all the main JavaScript functionality for the theme
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Mobile menu toggle
        const mobileMenuToggle = $('.mobile-menu-toggle');
        const mobileMenu = $('.mobile-menu');
        const mobileMenuClose = $('.mobile-menu-close');
        const body = $('body');

        mobileMenuToggle.on('click', function(e) {
            e.preventDefault();
            mobileMenu.toggleClass('active');
            body.toggleClass('mobile-menu-open');
        });

        mobileMenuClose.on('click', function(e) {
            e.preventDefault();
            mobileMenu.removeClass('active');
            body.removeClass('mobile-menu-open');
        });

        // Close mobile menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() > 1024) {
                mobileMenu.removeClass('active');
                body.removeClass('mobile-menu-open');
            }
        });

        // Dropdown menus
        $('.menu-item-has-children > a').on('click', function(e) {
            if ($(window).width() < 1025) {
                e.preventDefault();
                $(this).parent().toggleClass('active');
                $(this).next('.sub-menu').slideToggle(200);
            }
        });

        // Sticky header
        const header = $('.site-header');
        const headerHeight = header.outerHeight();
        let lastScrollTop = 0;

        if (header.hasClass('sticky-header')) {
            $(window).on('scroll', function() {
                const currentScrollTop = $(this).scrollTop();

                if (currentScrollTop > headerHeight) {
                    header.addClass('is-sticky');
                    body.css('padding-top', headerHeight + 'px');

                    if (currentScrollTop > lastScrollTop) {
                        // Scrolling down
                        header.removeClass('show-sticky');
                    } else {
                        // Scrolling up
                        header.addClass('show-sticky');
                    }
                } else {
                    header.removeClass('is-sticky show-sticky');
                    body.css('padding-top', '0');
                }

                lastScrollTop = currentScrollTop;
            });
        }

        // Mini cart toggle
        const miniCartToggle = $('.mini-cart-toggle');
        const miniCartDropdown = $('.mini-cart-dropdown');

        miniCartToggle.on('click', function(e) {
            e.preventDefault();
            miniCartDropdown.toggleClass('hidden');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.mini-cart').length) {
                miniCartDropdown.addClass('hidden');
            }
        });

        // Search toggle
        const searchToggle = $('.search-toggle');
        const searchDropdown = $('.search-dropdown');

        searchToggle.on('click', function(e) {
            e.preventDefault();
            searchDropdown.toggleClass('hidden');
            searchDropdown.find('input[type="search"]').focus();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-area').length) {
                searchDropdown.addClass('hidden');
            }
        });

        // Back to top button
        const backToTop = $('.back-to-top');

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                backToTop.addClass('show');
            } else {
                backToTop.removeClass('show');
            }
        });

        backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 800);
        });

        // Smooth scroll for anchor links
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800);
                    return false;
                }
            }
        });

        // Initialize tooltips
        $('[data-tooltip]').each(function() {
            $(this).on('mouseenter', function() {
                const tooltip = $('<div class="tooltip"></div>');
                tooltip.text($(this).data('tooltip'));
                $('body').append(tooltip);
                
                const position = $(this).offset();
                const tooltipWidth = tooltip.outerWidth();
                const tooltipHeight = tooltip.outerHeight();
                
                tooltip.css({
                    top: position.top - tooltipHeight - 10,
                    left: position.left - (tooltipWidth / 2) + ($(this).outerWidth() / 2)
                });
                
                tooltip.addClass('show');
            }).on('mouseleave', function() {
                $('.tooltip').remove();
            });
        });

        // Accordion
        $('.accordion-header').on('click', function() {
            const accordionItem = $(this).parent();
            const accordionContent = $(this).next('.accordion-content');
            
            if (accordionItem.hasClass('active')) {
                accordionItem.removeClass('active');
                accordionContent.slideUp(200);
            } else {
                $('.accordion-item').removeClass('active');
                $('.accordion-content').slideUp(200);
                accordionItem.addClass('active');
                accordionContent.slideDown(200);
            }
        });

        // Tabs
        $('.tabs-nav a').on('click', function(e) {
            e.preventDefault();
            
            const tabId = $(this).attr('href');
            
            $('.tabs-nav a').removeClass('active');
            $('.tab-content').removeClass('active');
            
            $(this).addClass('active');
            $(tabId).addClass('active');
        });

        // Product quantity buttons
        $('.quantity-button.plus').on('click', function(e) {
            e.preventDefault();
            const input = $(this).siblings('input.qty');
            const val = parseInt(input.val());
            input.val(val + 1).trigger('change');
        });

        $('.quantity-button.minus').on('click', function(e) {
            e.preventDefault();
            const input = $(this).siblings('input.qty');
            const val = parseInt(input.val());
            if (val > 1) {
                input.val(val - 1).trigger('change');
            }
        });

        // Product gallery
        if ($.fn.flexslider) {
            $('.product-gallery-slider').flexslider({
                animation: 'slide',
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                sync: '.product-gallery-thumbnails',
                touch: true,
                smoothHeight: true
            });
            
            $('.product-gallery-thumbnails').flexslider({
                animation: 'slide',
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                itemWidth: 100,
                itemMargin: 10,
                asNavFor: '.product-gallery-slider',
                touch: true,
                directionNav: true
            });
        }

        // Product quick view
        $('.quick-view-button').on('click', function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            const quickViewModal = $('#quick-view-modal');
            const quickViewContent = $('#quick-view-content');
            
            quickViewContent.html('<div class="loading-spinner"></div>');
            quickViewModal.removeClass('hidden');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    security: aqualuxeWooCommerce.quick_view_nonce
                },
                success: function(response) {
                    quickViewContent.html(response);
                    
                    // Re-initialize quantity buttons
                    $('.quantity-button.plus').on('click', function(e) {
                        e.preventDefault();
                        const input = $(this).siblings('input.qty');
                        const val = parseInt(input.val());
                        input.val(val + 1).trigger('change');
                    });
            
                    $('.quantity-button.minus').on('click', function(e) {
                        e.preventDefault();
                        const input = $(this).siblings('input.qty');
                        const val = parseInt(input.val());
                        if (val > 1) {
                            input.val(val - 1).trigger('change');
                        }
                    });
                }
            });
        });
        
        // Close quick view modal
        $('.quick-view-close').on('click', function(e) {
            e.preventDefault();
            $('#quick-view-modal').addClass('hidden');
        });
        
        // Close quick view modal on outside click
        $(document).on('click', function(e) {
            if ($(e.target).is('#quick-view-modal')) {
                $('#quick-view-modal').addClass('hidden');
            }
        });

        // Wishlist functionality
        $('.wishlist-button').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_wishlist',
                    product_id: productId,
                    nonce: aqualuxeWooCommerce.wishlist_nonce
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.action === 'added') {
                            button.addClass('added');
                            button.attr('title', aqualuxeWooCommerce.i18n_browse_wishlist);
                        } else {
                            button.removeClass('added');
                            button.attr('title', aqualuxeWooCommerce.i18n_add_to_wishlist);
                        }
                    }
                }
            });
        });

        // Remove from wishlist
        $('.remove-from-wishlist').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            const productItem = button.closest('.wishlist-product');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_wishlist',
                    product_id: productId,
                    nonce: aqualuxeWooCommerce.wishlist_nonce
                },
                success: function(response) {
                    if (response.success) {
                        productItem.fadeOut(300, function() {
                            $(this).remove();
                            
                            if ($('.wishlist-product').length === 0) {
                                $('.wishlist-products').html('<div class="woocommerce-info">' + aqualuxeWooCommerce.i18n_no_products_in_wishlist + '</div>');
                            }
                        });
                    }
                }
            });
        });

        // AJAX add to cart
        $(document).on('click', '.ajax_add_to_cart', function(e) {
            $(this).addClass('loading');
        });
        
        $(document).on('added_to_cart', function(e, fragments, cart_hash, button) {
            $(button).removeClass('loading');
            
            // Show mini cart
            $('.mini-cart-dropdown').removeClass('hidden');
            
            // Hide mini cart after 5 seconds
            setTimeout(function() {
                $('.mini-cart-dropdown').addClass('hidden');
            }, 5000);
        });

        // Currency switcher
        $('.currency-switcher-button').on('click', function(e) {
            e.preventDefault();
            $('.currency-switcher-dropdown').toggleClass('hidden');
        });
        
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.currency-switcher').length) {
                $('.currency-switcher-dropdown').addClass('hidden');
            }
        });

        // Language switcher
        $('.language-switcher-button').on('click', function(e) {
            e.preventDefault();
            $('.language-switcher-dropdown').toggleClass('hidden');
        });
        
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.language-switcher').length) {
                $('.language-switcher-dropdown').addClass('hidden');
            }
        });

        // Initialize sliders
        if ($.fn.slick) {
            // Hero slider
            $('.hero-slider').slick({
                dots: true,
                arrows: true,
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear',
                autoplay: true,
                autoplaySpeed: 5000,
                pauseOnHover: false
            });
            
            // Product carousel
            $('.product-carousel').slick({
                dots: false,
                arrows: true,
                infinite: true,
                speed: 500,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
            
            // Testimonial carousel
            $('.testimonial-carousel').slick({
                dots: true,
                arrows: false,
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 8000
            });
            
            // Brand carousel
            $('.brand-carousel').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 500,
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }

        // Initialize Isotope for filtering
        if (typeof Isotope !== 'undefined') {
            const $grid = $('.isotope-grid').isotope({
                itemSelector: '.isotope-item',
                layoutMode: 'fitRows'
            });
            
            $('.filter-buttons').on('click', 'button', function() {
                const filterValue = $(this).attr('data-filter');
                $grid.isotope({ filter: filterValue });
                
                $('.filter-buttons button').removeClass('active');
                $(this).addClass('active');
            });
        }

        // Initialize AOS (Animate on Scroll)
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        }

        // Newsletter form submission
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const submitButton = form.find('button[type="submit"]');
            const email = form.find('input[name="email"]').val();
            const responseMessage = form.find('.response-message');
            
            if (!email) {
                responseMessage.html('<div class="error-message">Please enter your email address.</div>');
                return;
            }
            
            submitButton.prop('disabled', true).addClass('loading');
            
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    submitButton.prop('disabled', false).removeClass('loading');
                    responseMessage.html('<div class="success-message">Thank you for subscribing!</div>');
                    form.find('input[name="email"]').val('');
                },
                error: function(xhr, status, error) {
                    submitButton.prop('disabled', false).removeClass('loading');
                    responseMessage.html('<div class="error-message">An error occurred. Please try again later.</div>');
                }
            });
        });

        // Contact form submission
        $('.contact-form').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const submitButton = form.find('button[type="submit"]');
            const responseMessage = form.find('.response-message');
            
            submitButton.prop('disabled', true).addClass('loading');
            
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    submitButton.prop('disabled', false).removeClass('loading');
                    responseMessage.html('<div class="success-message">Thank you for your message. We will get back to you soon!</div>');
                    form[0].reset();
                },
                error: function(xhr, status, error) {
                    submitButton.prop('disabled', false).removeClass('loading');
                    responseMessage.html('<div class="error-message">An error occurred. Please try again later.</div>');
                }
            });
        });

        // Initialize Google Maps
        if ($('#google-map').length > 0) {
            const mapElement = document.getElementById('google-map');
            const latitude = parseFloat(mapElement.getAttribute('data-latitude'));
            const longitude = parseFloat(mapElement.getAttribute('data-longitude'));
            const zoom = parseInt(mapElement.getAttribute('data-zoom'));
            
            if (latitude && longitude) {
                const mapOptions = {
                    center: { lat: latitude, lng: longitude },
                    zoom: zoom || 15,
                    styles: [
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#e9e9e9"
                                },
                                {
                                    "lightness": 17
                                }
                            ]
                        },
                        {
                            "featureType": "landscape",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#f5f5f5"
                                },
                                {
                                    "lightness": 20
                                }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.fill",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 17
                                }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 29
                                },
                                {
                                    "weight": 0.2
                                }
                            ]
                        },
                        {
                            "featureType": "road.arterial",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 18
                                }
                            ]
                        },
                        {
                            "featureType": "road.local",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 16
                                }
                            ]
                        },
                        {
                            "featureType": "poi",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#f5f5f5"
                                },
                                {
                                    "lightness": 21
                                }
                            ]
                        },
                        {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#dedede"
                                },
                                {
                                    "lightness": 21
                                }
                            ]
                        },
                        {
                            "elementType": "labels.text.stroke",
                            "stylers": [
                                {
                                    "visibility": "on"
                                },
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 16
                                }
                            ]
                        },
                        {
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "saturation": 36
                                },
                                {
                                    "color": "#333333"
                                },
                                {
                                    "lightness": 40
                                }
                            ]
                        },
                        {
                            "elementType": "labels.icon",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "transit",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#f2f2f2"
                                },
                                {
                                    "lightness": 19
                                }
                            ]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.fill",
                            "stylers": [
                                {
                                    "color": "#fefefe"
                                },
                                {
                                    "lightness": 20
                                }
                            ]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                {
                                    "color": "#fefefe"
                                },
                                {
                                    "lightness": 17
                                },
                                {
                                    "weight": 1.2
                                }
                            ]
                        }
                    ]
                };
                
                const map = new google.maps.Map(mapElement, mapOptions);
                
                const marker = new google.maps.Marker({
                    position: { lat: latitude, lng: longitude },
                    map: map,
                    title: 'AquaLuxe'
                });
            }
        }
    });

})(jQuery);