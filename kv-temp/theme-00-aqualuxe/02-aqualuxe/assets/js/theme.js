/**
 * AquaLuxe Theme JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Document ready
    $(document).ready(function() {
        // Initialize theme features
        AquaLuxe.init();
    });
    
    // AquaLuxe object
    window.AquaLuxe = {
        /**
         * Initialize theme features
         */
        init: function() {
            this.bindEvents();
            this.initMobileMenu();
            this.initProductGallery();
            this.initQuantityButtons();
            this.initAjaxAddToCart();
            this.initStickyHeader();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Mobile menu toggle
            $('.menu-toggle').on('click', this.toggleMobileMenu);
            
            // Quantity buttons
            $(document).on('click', '.quantity-button', this.handleQuantityButtonClick);
            
            // Ajax add to cart
            $(document).on('click', '.ajax_add_to_cart', this.handleAjaxAddToCart);
            
            // Window scroll
            $(window).on('scroll', this.handleWindowScroll);
        },
        
        /**
         * Initialize mobile menu
         */
        initMobileMenu: function() {
            var menuToggle = $('.menu-toggle');
            var mainNavigation = $('.main-navigation ul');
            
            menuToggle.on('click', function() {
                mainNavigation.toggleClass('active');
            });
        },
        
        /**
         * Initialize product gallery
         */
        initProductGallery: function() {
            // Only run on product pages
            if (!$('body').hasClass('single-product')) {
                return;
            }
            
            // Initialize product gallery if it exists
            if ($.fn.flexslider && $('.product-gallery').length) {
                $('.product-gallery').flexslider({
                    animation: 'slide',
                    controlNav: 'thumbnails',
                    directionNav: false,
                    smoothHeight: true
                });
            }
        },
        
        /**
         * Initialize quantity buttons
         */
        initQuantityButtons: function() {
            // Add quantity buttons to quantity inputs
            $('.quantity').each(function() {
                var quantityInput = $(this).find('input[type="number"]');
                var quantityValue = parseInt(quantityInput.val());
                
                // Add buttons if they don't exist
                if (!$(this).find('.quantity-button').length) {
                    quantityInput.before('<button type="button" class="quantity-button quantity-down">-</button>');
                    quantityInput.after('<button type="button" class="quantity-button quantity-up">+</button>');
                }
            });
        },
        
        /**
         * Initialize AJAX add to cart
         */
        initAjaxAddToCart: function() {
            // Only run if AJAX is enabled
            if (aqualuxe_vars.ajax_cart_enabled !== 'yes') {
                return;
            }
            
            // Add loading class to add to cart buttons
            $(document).on('click', '.add_to_cart_button', function() {
                $(this).addClass('loading');
            });
            
            // Remove loading class when AJAX is complete
            $(document.body).on('added_to_cart', function() {
                $('.add_to_cart_button').removeClass('loading');
            });
        },
        
        /**
         * Initialize sticky header
         */
        initStickyHeader: function() {
            var header = $('.site-header');
            var headerHeight = header.outerHeight();
            
            // Add padding to body to account for fixed header
            $('body').css('padding-top', headerHeight);
            
            // Add scroll event listener
            $(window).on('scroll', function() {
                if ($(window).scrollTop() > headerHeight) {
                    header.addClass('sticky');
                } else {
                    header.removeClass('sticky');
                }
            });
        },
        
        /**
         * Toggle mobile menu
         */
        toggleMobileMenu: function() {
            $('.main-navigation ul').toggleClass('active');
        },
        
        /**
         * Handle quantity button click
         */
        handleQuantityButtonClick: function() {
            var button = $(this);
            var quantityInput = button.siblings('input[type="number"]');
            var currentValue = parseInt(quantityInput.val());
            var maxValue = parseInt(quantityInput.attr('max')) || 0;
            var minValue = parseInt(quantityInput.attr('min')) || 0;
            
            // Increase or decrease quantity
            if (button.hasClass('quantity-up')) {
                if (maxValue === 0 || currentValue < maxValue) {
                    quantityInput.val(currentValue + 1);
                }
            } else if (button.hasClass('quantity-down')) {
                if (currentValue > minValue) {
                    quantityInput.val(currentValue - 1);
                }
            }
            
            // Trigger change event
            quantityInput.trigger('change');
        },
        
        /**
         * Handle AJAX add to cart
         */
        handleAjaxAddToCart: function() {
            var button = $(this);
            var productID = button.data('product_id');
            var quantity = button.siblings('.quantity').find('input[type="number"]').val() || 1;
            
            // Add loading class
            button.addClass('loading');
            
            // Send AJAX request
            $.post(aqualuxe_vars.ajax_url, {
                action: 'aqualuxe_add_to_cart',
                product_id: productID,
                quantity: quantity,
                nonce: aqualuxe_vars.nonce
            }, function(response) {
                // Remove loading class
                button.removeClass('loading');
                
                // Handle response
                if (response.success) {
                    // Update cart fragments
                    $(document.body).trigger('wc_fragment_refresh');
                    
                    // Show success message
                    AquaLuxe.showMessage(response.data, 'success');
                } else {
                    // Show error message
                    AquaLuxe.showMessage(response.data, 'error');
                }
            });
        },
        
        /**
         * Handle window scroll
         */
        handleWindowScroll: function() {
            // Add scrolled class to body when scrolled
            if ($(window).scrollTop() > 100) {
                $('body').addClass('scrolled');
            } else {
                $('body').removeClass('scrolled');
            }
        },
        
        /**
         * Show message
         */
        showMessage: function(message, type) {
            // Create message element
            var messageElement = $('<div class="aqualuxe-message ' + type + '">' + message + '</div>');
            
            // Add to page
            $('body').append(messageElement);
            
            // Remove after 3 seconds
            setTimeout(function() {
                messageElement.fadeOut(function() {
                    messageElement.remove();
                });
            }, 3000);
        },
        
        /**
         * Update cart count
         */
        updateCartCount: function(count) {
            $('.cart-count').text(count);
        },
        
        /**
         * Lazy load images
         */
        lazyLoadImages: function() {
            // Use Intersection Observer if available
            if ('IntersectionObserver' in window) {
                var imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            var src = img.dataset.src;
                            
                            if (src) {
                                img.src = src;
                                img.classList.remove('lazy');
                                observer.unobserve(img);
                            }
                        }
                    });
                });
                
                // Observe all lazy images
                var lazyImages = document.querySelectorAll('img.lazy');
                lazyImages.forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        },
        
        /**
         * Initialize lazy loading
         */
        initLazyLoading: function() {
            // Only run if Intersection Observer is available
            if ('IntersectionObserver' in window) {
                this.lazyLoadImages();
            }
        }
    };
    
    // Initialize lazy loading
    AquaLuxe.initLazyLoading();
    
})(jQuery);