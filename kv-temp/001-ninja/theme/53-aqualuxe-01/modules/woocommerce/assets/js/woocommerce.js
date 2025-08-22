/**
 * WooCommerce functionality
 *
 * @package AquaLuxe
 */

(function ($) {
    'use strict';

    const AqualuxeWooCommerce = {
        /**
         * Initialize
         */
        init: function () {
            this.bindEvents();
            this.initMiniCart();
            this.initQuantityButtons();
            this.initProductGallery();
        },

        /**
         * Bind events
         */
        bindEvents: function () {
            // Product tabs
            $('.wc-tabs-wrapper, .woocommerce-tabs').on('click', '.wc-tabs li a, ul.tabs li a', this.switchTab);
            
            // Single product add to cart
            $('form.cart').on('submit', this.addToCartAnimation);
            
            // AJAX add to cart buttons
            $(document.body).on('added_to_cart', this.addedToCart);
        },

        /**
         * Initialize mini cart
         */
        initMiniCart: function () {
            const $miniCart = $('.mini-cart');
            
            // Toggle mini cart dropdown
            $miniCart.on('click', '.mini-cart-link', function (e) {
                e.preventDefault();
                $(this).parent().toggleClass('mini-cart-open');
            });
            
            // Close mini cart when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.mini-cart').length) {
                    $miniCart.removeClass('mini-cart-open');
                }
            });
        },

        /**
         * Initialize quantity buttons
         */
        initQuantityButtons: function () {
            // Quantity buttons
            $(document).on('click', '.quantity-button', function () {
                const $button = $(this);
                const $input = $button.parent().find('input.qty');
                const oldValue = parseFloat($input.val());
                let newVal = oldValue;
                
                if ($button.hasClass('plus')) {
                    const max = parseFloat($input.attr('max'));
                    if (max && (max <= oldValue)) {
                        newVal = max;
                    } else {
                        newVal = oldValue + 1;
                    }
                } else {
                    const min = parseFloat($input.attr('min'));
                    if (min && (min >= oldValue)) {
                        newVal = min;
                    } else if (oldValue > 1) {
                        newVal = oldValue - 1;
                    }
                }
                
                $input.val(newVal).trigger('change');
            });
            
            // Add quantity buttons
            $('.quantity:not(.buttons-added)').addClass('buttons-added').append('<button type="button" class="quantity-button plus">+</button>').prepend('<button type="button" class="quantity-button minus">-</button>');
        },

        /**
         * Initialize product gallery
         */
        initProductGallery: function () {
            // Product gallery zoom
            $('.woocommerce-product-gallery__image a').zoom({
                touch: false
            });
        },

        /**
         * Switch tab
         *
         * @param {Event} e Event
         */
        switchTab: function (e) {
            e.preventDefault();
            const $tab = $(this);
            const $tabs = $tab.closest('.wc-tabs, ul.tabs');
            const $panels = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs').find('.woocommerce-Tabs-panel, .panel');
            const index = $tabs.find('li').index($tab.parent());
            
            $tabs.find('li').removeClass('active');
            $tab.parent().addClass('active');
            
            $panels.hide().eq(index).show();
        },

        /**
         * Add to cart animation
         *
         * @param {Event} e Event
         */
        addToCartAnimation: function (e) {
            // Animation code here if needed
        },

        /**
         * Added to cart
         *
         * @param {Event} e Event
         * @param {Array} fragments Fragments
         * @param {string} cart_hash Cart hash
         * @param {jQuery} $button Button
         */
        addedToCart: function (e, fragments, cart_hash, $button) {
            // Open mini cart
            $('.mini-cart').addClass('mini-cart-open');
            
            // Auto close after 5 seconds
            setTimeout(function () {
                $('.mini-cart').removeClass('mini-cart-open');
            }, 5000);
        }
    };

    // Initialize on document ready
    $(document).ready(function () {
        AqualuxeWooCommerce.init();
    });

})(jQuery);