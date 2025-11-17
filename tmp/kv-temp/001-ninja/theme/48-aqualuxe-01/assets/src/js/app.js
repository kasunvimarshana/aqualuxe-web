/**
 * AquaLuxe Theme JavaScript
 * 
 * Main JavaScript file for the AquaLuxe theme.
 */

// Import components
import './components/dark-mode';
import './components/mobile-menu';
import './components/product-gallery';
import './components/ajax-cart';

(function($) {
    'use strict';

    // Global variables
    const $window = $(window);
    const $document = $(document);
    const $body = $('body');
    const $html = $('html');
    const $siteHeader = $('.site-header');
    const isMobile = window.matchMedia('(max-width: 991px)').matches;
    const isRTL = $body.hasClass('rtl');

    /**
     * AquaLuxe Theme Object
     */
    const AquaLuxe = {
        /**
         * Initialize the theme
         */
        init: function() {
            this.stickyHeader();
            this.searchToggle();
            this.dropdownMenu();
            this.backToTop();
            this.smoothScroll();
            this.pageLoader();
            this.quantityButtons();
            this.quickView();
            this.wishlist();
            this.compare();
            this.shopFilters();
            this.viewSwitcher();
            this.newsletterPopup();
            this.initSliders();
            this.initAccordions();
            this.initTabs();
            this.initTooltips();
            this.initCountdown();
            this.initMasonry();
            this.initLazyLoad();
            this.initAnimations();
            this.initMaps();
            this.initLanguageSwitcher();
            this.initCurrencySwitcher();
            this.initVendorDashboard();
        },

        /**
         * Sticky header functionality
         */
        stickyHeader: function() {
            if ($siteHeader.hasClass('sticky-header')) {
                const headerHeight = $siteHeader.outerHeight();
                let lastScrollTop = 0;
                
                $window.on('scroll', function() {
                    const scrollTop = $window.scrollTop();
                    
                    if (scrollTop > headerHeight) {
                        $siteHeader.addClass('is-sticky');
                        $body.css('padding-top', headerHeight);
                        
                        // Hide on scroll down, show on scroll up
                        if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
                            $siteHeader.addClass('is-hidden');
                        } else {
                            $siteHeader.removeClass('is-hidden');
                        }
                    } else {
                        $siteHeader.removeClass('is-sticky is-hidden');
                        $body.css('padding-top', 0);
                    }
                    
                    lastScrollTop = scrollTop;
                });
            }
        },

        /**
         * Search toggle functionality
         */
        searchToggle: function() {
            const $searchToggle = $('.search-toggle');
            const $searchForm = $('.search-form-dropdown');
            
            $searchToggle.on('click', function(e) {
                e.preventDefault();
                $searchForm.toggleClass('active');
                
                if ($searchForm.hasClass('active')) {
                    setTimeout(function() {
                        $searchForm.find('input[type="search"]').focus();
                    }, 100);
                }
            });
            
            // Close search when clicking outside
            $document.on('click', function(e) {
                if ($searchForm.hasClass('active') && 
                    !$(e.target).closest('.search-form-dropdown').length && 
                    !$(e.target).closest('.search-toggle').length) {
                    $searchForm.removeClass('active');
                }
            });
        },

        /**
         * Dropdown menu functionality
         */
        dropdownMenu: function() {
            if (!isMobile) {
                $('.primary-menu .menu-item-has-children').hover(
                    function() {
                        $(this).addClass('submenu-open');
                        $(this).find('> .sub-menu').stop(true, true).fadeIn(300);
                    },
                    function() {
                        $(this).removeClass('submenu-open');
                        $(this).find('> .sub-menu').stop(true, true).fadeOut(300);
                    }
                );
            }
        },

        /**
         * Back to top button functionality
         */
        backToTop: function() {
            const $backToTop = $('#back-to-top');
            
            $window.on('scroll', function() {
                if ($window.scrollTop() > 300) {
                    $backToTop.addClass('show');
                } else {
                    $backToTop.removeClass('show');
                }
            });
            
            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        },

        /**
         * Smooth scroll functionality
         */
        smoothScroll: function() {
            // Smooth scroll for anchor links
            $('a[href*="#"]:not([href="#"])').on('click', function() {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                    location.hostname === this.hostname) {
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
        },

        /**
         * Page loader functionality
         */
        pageLoader: function() {
            const $pageLoader = $('#page-loader');
            
            if ($pageLoader.length) {
                $window.on('load', function() {
                    $pageLoader.fadeOut(500, function() {
                        $pageLoader.remove();
                    });
                });
                
                // Fallback if load event doesn't trigger
                setTimeout(function() {
                    $pageLoader.fadeOut(500);
                }, 2000);
            }
        },

        /**
         * Quantity buttons functionality
         */
        quantityButtons: function() {
            // Add quantity buttons if they don't exist
            $('.quantity').each(function() {
                const $quantity = $(this);
                
                if (!$quantity.find('.quantity-button').length) {
                    $quantity.prepend('<button type="button" class="quantity-button minus">-</button>');
                    $quantity.append('<button type="button" class="quantity-button plus">+</button>');
                }
            });
            
            // Quantity button click
            $document.on('click', '.quantity-button', function() {
                const $button = $(this);
                const $input = $button.parent().find('input.qty');
                const oldValue = parseFloat($input.val());
                let newVal = 0;
                
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
         * Quick view functionality
         */
        quickView: function() {
            $document.on('click', '.product-quick-view', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (productId) {
                    $body.addClass('loading');
                    
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_quick_view',
                            product_id: productId,
                            security: aqualuxeData.quickViewNonce
                        },
                        success: function(response) {
                            $body.removeClass('loading');
                            
                            if (response.success) {
                                // Create modal
                                const $modal = $('<div class="quick-view-modal"></div>');
                                const $overlay = $('<div class="quick-view-overlay"></div>');
                                const $content = $('<div class="quick-view-content"></div>');
                                const $close = $('<button class="quick-view-close"><i class="fas fa-times"></i></button>');
                                
                                $content.html(response.data.html);
                                $modal.append($close).append($content);
                                $body.append($overlay).append($modal);
                                
                                // Initialize product gallery and variations
                                $modal.find('.woocommerce-product-gallery').each(function() {
                                    $(this).wc_product_gallery();
                                });
                                
                                $modal.find('form.variations_form').each(function() {
                                    $(this).wc_variation_form();
                                });
                                
                                // Add quantity buttons
                                AquaLuxe.quantityButtons();
                                
                                // Show modal
                                setTimeout(function() {
                                    $overlay.addClass('active');
                                    $modal.addClass('active');
                                }, 100);
                                
                                // Close modal
                                $close.add($overlay).on('click', function() {
                                    $overlay.removeClass('active');
                                    $modal.removeClass('active');
                                    
                                    setTimeout(function() {
                                        $overlay.remove();
                                        $modal.remove();
                                    }, 500);
                                });
                            }
                        }
                    });
                }
            });
        },

        /**
         * Wishlist functionality
         */
        wishlist: function() {
            $document.on('click', '.product-wishlist', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (productId) {
                    $button.addClass('loading');
                    
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_wishlist',
                            product_id: productId,
                            security: aqualuxeData.wishlistNonce
                        },
                        success: function(response) {
                            $button.removeClass('loading');
                            
                            if (response.success) {
                                $button.toggleClass('active');
                                
                                // Update wishlist count
                                $('.wishlist-count').text(response.data.count);
                                
                                // Show notification
                                AquaLuxe.showNotification(response.data.message, 'success');
                            } else {
                                // Show error notification
                                AquaLuxe.showNotification(response.data.message, 'error');
                            }
                        }
                    });
                }
            });
        },

        /**
         * Compare functionality
         */
        compare: function() {
            $document.on('click', '.product-compare', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (productId) {
                    $button.addClass('loading');
                    
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_compare',
                            product_id: productId,
                            security: aqualuxeData.compareNonce
                        },
                        success: function(response) {
                            $button.removeClass('loading');
                            
                            if (response.success) {
                                $button.toggleClass('active');
                                
                                // Update compare count
                                $('.compare-count').text(response.data.count);
                                
                                // Show notification
                                AquaLuxe.showNotification(response.data.message, 'success');
                            } else {
                                // Show error notification
                                AquaLuxe.showNotification(response.data.message, 'error');
                            }
                        }
                    });
                }
            });
        },

        /**
         * Shop filters functionality
         */
        shopFilters: function() {
            const $shopFilterToggle = $('.shop-filter-toggle');
            const $shopFilterContent = $('.shop-filter-content');
            
            $shopFilterToggle.on('click', function() {
                $shopFilterContent.slideToggle(300);
                $shopFilterToggle.toggleClass('active');
            });
        },

        /**
         * View switcher functionality
         */
        viewSwitcher: function() {
            const $viewSwitcher = $('.shop-view-button');
            const $productsWrapper = $('.products');
            
            // Get saved view
            const savedView = localStorage.getItem('aqualuxe_shop_view') || 'grid';
            
            // Set initial view
            $productsWrapper.removeClass('view-grid view-list').addClass('view-' + savedView);
            $('.shop-view-button[data-view="' + savedView + '"]').addClass('active');
            
            // Switch view
            $viewSwitcher.on('click', function() {
                const view = $(this).data('view');
                
                $viewSwitcher.removeClass('active');
                $(this).addClass('active');
                
                $productsWrapper.removeClass('view-grid view-list').addClass('view-' + view);
                
                // Save view preference
                localStorage.setItem('aqualuxe_shop_view', view);
            });
        },

        /**
         * Newsletter popup functionality
         */
        newsletterPopup: function() {
            const $newsletterPopup = $('.newsletter-popup');
            
            if ($newsletterPopup.length) {
                // Check if popup was closed before
                const popupClosed = localStorage.getItem('aqualuxe_newsletter_closed');
                
                if (!popupClosed) {
                    // Show popup after 5 seconds
                    setTimeout(function() {
                        $newsletterPopup.addClass('active');
                    }, 5000);
                }
                
                // Close popup
                $('.newsletter-popup-close').on('click', function() {
                    $newsletterPopup.removeClass('active');
                    
                    // Save to localStorage
                    if ($('#newsletter-dont-show').is(':checked')) {
                        localStorage.setItem('aqualuxe_newsletter_closed', 'true');
                    }
                });
                
                // Close when clicking outside
                $document.on('click', function(e) {
                    if ($newsletterPopup.hasClass('active') && 
                        !$(e.target).closest('.newsletter-popup-content').length) {
                        $newsletterPopup.removeClass('active');
                    }
                });
            }
        },

        /**
         * Initialize sliders
         */
        initSliders: function() {
            // Check if slick is available
            if ($.fn.slick) {
                // Hero slider
                $('.hero-slider').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    fade: true,
                    speed: 1000,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    rtl: isRTL
                });
                
                // Products slider
                $('.products-slider').each(function() {
                    const $slider = $(this);
                    const slidesToShow = $slider.data('slides-to-show') || 4;
                    
                    $slider.slick({
                        slidesToShow: slidesToShow,
                        slidesToScroll: 1,
                        arrows: true,
                        dots: false,
                        autoplay: false,
                        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                        rtl: isRTL,
                        responsive: [
                            {
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: Math.min(slidesToShow, 3)
                                }
                            },
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: Math.min(slidesToShow, 2)
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 1
                                }
                            }
                        ]
                    });
                });
                
                // Testimonials slider
                $('.testimonials-slider').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    rtl: isRTL
                });
                
                // Brands slider
                $('.brands-slider').slick({
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: false,
                    autoplay: true,
                    autoplaySpeed: 3000,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    rtl: isRTL,
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 5
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 4
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 2
                            }
                        }
                    ]
                });
            }
        },

        /**
         * Initialize accordions
         */
        initAccordions: function() {
            $('.accordion-item-header').on('click', function() {
                const $header = $(this);
                const $item = $header.parent();
                const $content = $header.next('.accordion-item-content');
                
                if ($item.hasClass('active')) {
                    $item.removeClass('active');
                    $content.slideUp(300);
                } else {
                    $item.siblings('.active').removeClass('active').find('.accordion-item-content').slideUp(300);
                    $item.addClass('active');
                    $content.slideDown(300);
                }
            });
        },

        /**
         * Initialize tabs
         */
        initTabs: function() {
            $('.tabs-nav a').on('click', function(e) {
                e.preventDefault();
                
                const $link = $(this);
                const tabId = $link.attr('href');
                
                $link.parent().addClass('active').siblings().removeClass('active');
                $(tabId).addClass('active').siblings('.tab-content').removeClass('active');
            });
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            // Check if tooltip plugin is available
            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                // Simple tooltip implementation
                $('.tooltip').each(function() {
                    const $tooltip = $(this);
                    const title = $tooltip.attr('title');
                    
                    if (title) {
                        $tooltip.append('<span class="tooltip-text">' + title + '</span>');
                        $tooltip.removeAttr('title');
                    }
                });
            }
        },

        /**
         * Initialize countdown
         */
        initCountdown: function() {
            // Check if countdown plugin is available
            if ($.fn.countdown) {
                $('.countdown').each(function() {
                    const $countdown = $(this);
                    const finalDate = $countdown.data('countdown');
                    
                    $countdown.countdown(finalDate, function(event) {
                        $countdown.html(event.strftime(
                            '<div class="countdown-item"><span class="countdown-value">%D</span><span class="countdown-label">Days</span></div>' +
                            '<div class="countdown-item"><span class="countdown-value">%H</span><span class="countdown-label">Hours</span></div>' +
                            '<div class="countdown-item"><span class="countdown-value">%M</span><span class="countdown-label">Minutes</span></div>' +
                            '<div class="countdown-item"><span class="countdown-value">%S</span><span class="countdown-label">Seconds</span></div>'
                        ));
                    });
                });
            }
        },

        /**
         * Initialize masonry layout
         */
        initMasonry: function() {
            // Check if masonry plugin is available
            if ($.fn.masonry) {
                $('.masonry-grid').masonry({
                    itemSelector: '.masonry-item',
                    columnWidth: '.masonry-sizer',
                    percentPosition: true
                });
            }
        },

        /**
         * Initialize lazy loading
         */
        initLazyLoad: function() {
            if (typeof LazyLoad !== 'undefined') {
                new LazyLoad({
                    elements_selector: '.lazy',
                    threshold: 300,
                    callback_loaded: function(element) {
                        $(element).parent().addClass('lazy-loaded');
                    }
                });
            }
        },

        /**
         * Initialize animations
         */
        initAnimations: function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 1000,
                    once: true,
                    offset: 100
                });
            }
        },

        /**
         * Initialize Google Maps
         */
        initMaps: function() {
            const $map = $('#google-map');
            
            if ($map.length && typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                const latitude = parseFloat($map.data('latitude')) || 0;
                const longitude = parseFloat($map.data('longitude')) || 0;
                const zoom = parseInt($map.data('zoom')) || 14;
                
                const mapOptions = {
                    center: {lat: latitude, lng: longitude},
                    zoom: zoom,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
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
                
                const map = new google.maps.Map($map[0], mapOptions);
                
                const marker = new google.maps.Marker({
                    position: {lat: latitude, lng: longitude},
                    map: map,
                    icon: {
                        url: aqualuxeData.themeUri + '/assets/images/map-marker.png',
                        scaledSize: new google.maps.Size(40, 40)
                    },
                    animation: google.maps.Animation.DROP
                });
            }
        },

        /**
         * Initialize language switcher
         */
        initLanguageSwitcher: function() {
            $('.language-switcher-current').on('click', function() {
                $(this).parent().toggleClass('active');
            });
            
            // Close when clicking outside
            $document.on('click', function(e) {
                if (!$(e.target).closest('.language-switcher').length) {
                    $('.language-switcher').removeClass('active');
                }
            });
        },

        /**
         * Initialize currency switcher
         */
        initCurrencySwitcher: function() {
            $('.currency-switcher-current').on('click', function() {
                $(this).parent().toggleClass('active');
            });
            
            // Close when clicking outside
            $document.on('click', function(e) {
                if (!$(e.target).closest('.currency-switcher').length) {
                    $('.currency-switcher').removeClass('active');
                }
            });
            
            // Currency switching
            $('.currency-switcher-item a').on('click', function(e) {
                e.preventDefault();
                
                const $link = $(this);
                const currency = $link.data('currency');
                
                if (currency) {
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_switch_currency',
                            currency: currency,
                            security: aqualuxeData.currencyNonce
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        },

        /**
         * Initialize vendor dashboard
         */
        initVendorDashboard: function() {
            // Vendor dashboard tabs
            $('.vendor-dashboard-tab a').on('click', function(e) {
                const $link = $(this);
                const section = $link.data('section');
                
                if (section) {
                    e.preventDefault();
                    
                    $link.parent().addClass('active').siblings().removeClass('active');
                    $('.vendor-dashboard-section').removeClass('active');
                    $('.vendor-dashboard-section-' + section).addClass('active');
                    
                    // Update URL
                    history.pushState(null, null, $link.attr('href'));
                }
            });
        },

        /**
         * Show notification
         * 
         * @param {string} message Notification message
         * @param {string} type Notification type (success, error, warning, info)
         */
        showNotification: function(message, type) {
            const $notification = $('<div class="notification notification-' + type + '"></div>');
            const $message = $('<div class="notification-message">' + message + '</div>');
            const $close = $('<button class="notification-close"><i class="fas fa-times"></i></button>');
            
            $notification.append($message).append($close);
            $body.append($notification);
            
            setTimeout(function() {
                $notification.addClass('active');
            }, 100);
            
            setTimeout(function() {
                $notification.removeClass('active');
                
                setTimeout(function() {
                    $notification.remove();
                }, 500);
            }, 5000);
            
            $close.on('click', function() {
                $notification.removeClass('active');
                
                setTimeout(function() {
                    $notification.remove();
                }, 500);
            });
        }
    };

    // Make AquaLuxe globally available
    window.AquaLuxe = AquaLuxe;

    // Initialize AquaLuxe theme
    $(document).ready(function() {
        AquaLuxe.init();
    });

})(jQuery);